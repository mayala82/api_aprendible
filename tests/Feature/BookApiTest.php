<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookApiTest extends TestCase
{

    use RefreshDatabase;
   
    /** @test */
    function can_all(){

        $books = Book::factory(3)->create();
        
        $response = $this->getJson(route('books.index')); 
 
        $response->assertJsonFragment([
          'title'=>$books[0]->title,
        ])->assertJsonFragment([
          'title'=>$books[1]->title,
        ]);

    }

    /** @test */
    function can_one(){
        $book = Book::factory()->create();
        $response = $this->getJson(route('books.show', $book));
        $response->assertJsonFragment([
         'title'=>$book->title,
        ]);

        $this->assertDatabaseHas('books',['title'=>$book->title]);
    }

    /** @test */
    function can_create(){
        $this->postJson(route('books.store', []))
             ->assertJsonValidationErrorFor('title'); 

        $response = $this->postJson(route('books.store', [
            'title'=>'Nuevo'
        ])); 

        $response->assertJsonFragment(["title"=>'Nuevo']); 
        $this->assertDatabaseHas('books',['title'=>'Nuevo']);

    }

    /** @test */
    function can_update(){

        $book = Book::factory()->create();
        $this->patchJson(route('books.update',$book), [])
             ->assertJsonValidationErrorFor('title'); 

        $this->patchJson(route('books.update',$book), ['title'=>'Editado' ])
             ->assertJsonFragment(["title"=>'Editado']);

        $this->assertDatabaseHas('books',['title'=>'Editado']);

    }

     /** @test */
    function can_delete(){
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy',$book))
             ->assertNoContent();
        $this->assertDatabaseCount('books',0);

    }
}
