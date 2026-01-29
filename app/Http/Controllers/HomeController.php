<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $books = [
            [
                'id' => 1,
                'title' => "It",
                'author' => "Stephen King",
                'description' => "The story follows seven children as they are terrorized by an evil entity called It, which exploits the fears of its victims to disguise itself while hunting its prey. It is a monstrous, shapeshifting predator that primarily appears in the form of Pennywise the Dancing Clown to attract its preferred prey of young children.",
                'genre' => "Horror",
                'cover' => "https://i.pinimg.com/originals/e1/20/f5/e120f54d54d968386791fef37e1309fa.jpg",
                'is18Plus' => true,
                'ranking' => 4.6
            ],
            [
                'id' => 2,
                'title' => "The Mist",
                'author' => "Stephen King",
                'description' => "In the story, the small town of Bridgton, Maine is shrouded in a dense mist that conceals otherworldly creatures. The protagonist and narrator David Drayton, who has taken refuge with his young son in a supermarket, tries to survive against not only the creatures of the mist, but also fanatical aggression from other survivors.",
                'genre' => "Psychological horror",
                'cover' => "https://i.pinimg.com/originals/e0/00/79/e00079ff4066fc25344ce774164e9c43.jpg",
                'is18Plus' => true,
                'ranking' => 0
            ],
            [
                'id' => 3,
                'title' => "Flowers for Algernon",
                'author' => "Daniel Keyes",
                'description' => "Algernon is a laboratory mouse who has undergone surgery to increase his intelligence. The story is told by a series of progress reports written by Charlie Gordon, the first human subject for the surgery, and it touches on ethical and moral themes such as the treatment of the mentally disabled.",
                'genre' => "Science fiction",
                'cover' => "https://routenote-push.s3.eu-west-2.amazonaws.com/448117/dd28cd35-4e58-42cd-bfc3-9df53f4fab91",
                'is18Plus' => false,
                'ranking' => 4.6
            ],
            [
                'id' => 4,
                'title' => "Doctor Zhivago",
                'author' => "Boris Pasternak",
                'description' => "The novel is named after its protagonist, Yuri Zhivago, a physician and poet, and takes place between the Russian Revolution of 1905 and World War II. The manuscript was smuggled to Milan and published in 1957. Pasternak was awarded the Nobel Prize for Literature, an event that enraged the Communist Party of USSR.",
                'genre' => "Romantic novel",
                'cover' => "https://baneslibraries.co.uk/wp-content/uploads/2024/04/Doctor-Zhivago-by-Boris-Pasternak.png",
                'is18Plus' => false,
                'ranking' => 4.1
            ],
            [
                'id' => 5,
                'title' => "Martin Eden",
                'author' => "Jack London",
                'description' => "This novel is about a young proletarian autodidact struggling to become a writer. Eden represents writers' frustration with publishers. The central theme of Eden's developing artistic sensibilities places the novel in the tradition of the Künstlerroman, which narrates an artist's formation and development.",
                'genre' => "Drama",
                'cover' => "https://spblib.ru/en/catalog?p_p_id=ru_spb_iac_esbo_portal_catalog_CatalogPortlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_resource_id=%2Fcover&p_p_cacheability=cacheLevelPage&_ru_spb_iac_esbo_portal_catalog_CatalogPortlet_url=http%3A%2F%2Fftp.libs.spb.ru%2Fcovers%2Fimages%2Fcover_ukr802_2019-12-20_18-30-58.jpg&_ru_spb_iac_esbo_portal_catalog_CatalogPortlet_mvcRenderCommandName=%2Fpublication&_ru_spb_iac_esbo_portal_catalog_CatalogPortlet_publicationId=12316890&_ru_spb_iac_esbo_portal_catalog_CatalogPortlet_slug=ulysses",
                'is18Plus' => false,
                'ranking' => 0
            ]
        ];

        return Inertia::render('Home', [
            'books' => $books
        ]);
    }
}
