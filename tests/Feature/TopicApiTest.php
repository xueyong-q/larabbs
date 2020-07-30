<?php

namespace Tests\Feature;

use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\ActingJWTUser;

class TopicApiTest extends TestCase
{
    use RefreshDatabase;
    use ActingJWTUser;

    protected $uri = '/api/v1/topics';

    protected $response = [
        'data', 'links', 'meta',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(); // 运行数据填充器
    }

    public function testStoreTopic()
    {
        $data = ['category_id' => 1, 'body' => 'test body', 'title' => 'test title'];
        $response = $this->JWTActingAs()->json('POST', $this->uri, $data);

        $asserData = [
            'category_id' => 1,
            'user_id' => $this->user->id,
            'title' => 'test title',
            'body' => clean('test body', 'user_topic_body'),
        ];

        $response->assertStatus(201)
            ->assertJsonFragment($asserData);
    }

    public function testIndexTopic()
    {
        $data = ['category_id' => 2, 'include' => 'user,category'];
        $response = $this->json('GET', $this->uri, $data);
        $response->assertStatus(200)->assertJsonStructure($this->response);

        // $response->dump();
    }

    public function testUserIndexTopic()
    {
        $data = ['include' => 'category,user'];
        $response = $this->json('GET', '/api/v1/users/1/topics', $data);

        $response->assertStatus(200)->assertJsonStructure($this->response);
        // $response->dump();
    }

    public function testShowTopic()
    {
        $topic = factory(Topic::class)->create(['user_id' => 2, 'category_id' => 2]);
        $data = ['include' => 'category,user'];
        $response = $this->json('GET', $this->uri . '/' . $topic->id, $data);

        $asserData = [
            'id', 'title', 'body'
        ];

        $response->assertStatus(200)->assertJsonStructure($asserData);

        // $response->dump();
    }

    public function testUpdateTopic()
    {
        $actingAs = $this->JWTActingAs();
        $topic = factory(Topic::class)->create(['user_id' => $this->user->id, 'category_id' => 2]);
        $data = ['title' => 'update title', 'body' => 'update body', 'category_id' => 3];
        $response = $actingAs->json('PATCH', $this->uri . '/' . $topic->id, $data);

        $asserData = [
            'category_id' => 3,
            'user_id' => $this->user->id,
            'title' => 'update title',
            'body' => clean('update body', 'user_topic_body'),
        ];

        $response->assertStatus(200)
            ->assertJsonFragment($asserData);
    }

    public function testDeleteTopic()
    {
        $actingAs = $this->JWTActingAs();
        $topic = factory(Topic::class)->create(['user_id' => $this->user->id, 'category_id' => 2]);
        $response = $actingAs->json('DELETE', $this->uri . '/' . $topic->id);

        // $response->dump();
        $response->assertStatus(204);
    }
}
