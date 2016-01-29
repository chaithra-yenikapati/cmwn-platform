<?php

use Illuminate\Database\Seeder;
use app\Game;

class GameTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        DB::table("games")->delete();

        $this->command->info("Creating Games!");

        $games[] = Game::create(array(
            "uuid" => "polar-bear",
            "title" => "Polar Bear",
            "description" => "The magnificent Polar Bear is in danger of becoming extinct!  Find out all about where they live and why their fur looks white.  (Hint:  Things are not always as they appear!)  You get to play and do an offline experiment."
        ));

        $games[] = Game::create(array(
            "uuid" => "sea-turtle",
            "title" => "Sea Turtle",
            "description" => "Can you ID the different kinds of animals?  Prove it or learn it right here!  Quick and fun, let your fingers do the clicking and be ready to show your stuff."
        ));

        $games[] = Game::create(array(
            "uuid" => "animal-id",
            "title" => "Animal ID",
            "description" => "Sea Turtles are wondrous creatures!  Get cool turtle facts, play games and find out why they are endangered."
        ));

        $games[] = Game::create(array(
            "uuid" => "litter-bug",
            "title" => "Litterbug",
            "description" => "Litter is destructive to our environment and wildlife – see it, experience it, fix it! Nothing brings it home better than a song that is so fun to sing they simply cannot forget the message.  Don't be a litterbug!  And now they won't!"
        ));

        $games[] = Game::create(array(
            "uuid" => "be-bright",
            "title" => "Be Bright",
            "description" => "Become a Light Saver agent of change!  This music video will kick your inner hero into high gear!"
        ));

        $games[] = Game::create(array(
            "uuid" => "meerkat-mania",
            "title" => "Meerkat Mania",
            "description" => "You will learn about fascinating beasts, but don't be surprised to find so much more. A fun video gives you the scoop and the \"Meerkat Move!\" What's the move? Do the Action Item and discover how important you can be to your friends."
        ));

        $games[] = Game::create(array(
            "uuid" => "fire",
            "title" => "FIRE!!!",
            "description" => ""
        ));

        $games[] = Game::create(array(
            "uuid" => "drought-out",
            "title" => "DroughtOUT",
            "description" => "Want to be part of the solution for the biggest issue in our world?  You came to the right place!  Starts right here!"
        ));

        $games[] = Game::create(array(
            "uuid" => "twirl-n-swirl",
            "title" => "Twirl n' Swirl",
            "description" => ""
        ));

        $this->command->info("Games Created!");
    }
}
