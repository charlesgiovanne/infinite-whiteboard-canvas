<?php

namespace Tests\Feature;

use App\Models\Board;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BoardApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_boards()
    {
        Board::create(['name' => 'Board A', 'canvas_data' => '{"shapes": []}']);
        Board::create(['name' => 'Board B']);

        $response = $this->getJson('/api/boards');

        $response->assertStatus(200)
                 ->assertJsonCount(2)
                 ->assertJsonFragment(['name' => 'Board A'])
                 ->assertJsonFragment(['name' => 'Board B']);
    }

    public function test_can_create_board()
    {
        $response = $this->postJson('/api/boards', [
            'name' => 'New Board',
            'canvas_data' => '{"version":"1.0"}'
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('name', 'New Board')
                 ->assertJsonPath('canvas_data', '{"version":"1.0"}');

        $this->assertDatabaseHas('boards', ['name' => 'New Board']);
    }

    public function test_cannot_create_board_with_duplicate_name()
    {
        Board::create(['name' => 'Unique Name']);

        $response = $this->postJson('/api/boards', [
            'name' => 'Unique Name',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name']);
    }

    public function test_can_get_single_board()
    {
        $board = Board::create(['name' => 'Single Board', 'canvas_data' => '{}']);

        $response = $this->getJson("/api/boards/{$board->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('name', 'Single Board');
    }

    public function test_can_update_board()
    {
        $board = Board::create(['name' => 'Old Name', 'canvas_data' => '{}']);

        $response = $this->putJson("/api/boards/{$board->id}", [
            'name' => 'Updated Name',
            'canvas_data' => '{"draw": true}'
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('name', 'Updated Name')
                 ->assertJsonPath('canvas_data', '{"draw": true}');

        $this->assertDatabaseHas('boards', ['name' => 'Updated Name']);
    }

    public function test_can_delete_board()
    {
        $board = Board::create(['name' => 'To Delete']);

        $response = $this->deleteJson("/api/boards/{$board->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('boards', ['id' => $board->id]);
    }
}
