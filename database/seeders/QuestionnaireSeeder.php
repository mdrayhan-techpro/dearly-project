<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\QuestionOption;
use Illuminate\Database\Seeder;

class QuestionnaireSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create the 4 standard questionnaires
        $lengths = [20, 30, 40, 50];
        $questionnaires = [];

        foreach ($lengths as $len) {
            $questionnaires[$len] = Questionnaire::updateOrCreate(
                ['length' => $len, 'version' => 1],
                [
                    'title' => "{$len}-Question Correspondence Discovery",
                    'is_active' => true,
                ]
            );
        }

        // 2. Comprehensive bank of 100+ unique, thoughtful questions covering all 17 dimensions
        $rawQuestions = [
            // Dimension: communication_style (7 questions)
            [
                'dim' => 'communication_style',
                'q' => 'When expressing your thoughts in writing, how would you describe your natural tone?',
                'options' => [
                    'Gentle, poetic, and reflective',
                    'Warm, conversational, and direct',
                    'Analytical, structured, and curious',
                    'Playful, witty, and spirited',
                    'Calm, brief, and measured',
                ]
            ],
            [
                'dim' => 'communication_style',
                'q' => 'How quickly do you prefer to reply to a heartfelt letter?',
                'options' => [
                    'Within a day or two while the feelings are fresh',
                    'Within a week after letting thoughts settle',
                    'Over several weeks when a quiet afternoon arrives',
                    'Whenever inspiration strikes, without strict schedules',
                    'I enjoy the slowest, most patient correspondence possible',
                ]
            ],
            [
                'dim' => 'communication_style',
                'q' => 'What length of letter feels most natural for you to write?',
                'options' => [
                    'A single concise page capturing one clear thought',
                    'Two to three pages blending news and reflection',
                    'A multi-page journal exploring various topics in depth',
                    'A brief postcard-style note with a vivid vignette',
                    'Varying freely depending on the week and mood',
                ]
            ],
            [
                'dim' => 'communication_style',
                'q' => 'How do you tend to open a personal letter to a friend?',
                'options' => [
                    'Reflecting on the weather, season, or quiet surroundings',
                    'Directly addressing something memorable they previously wrote',
                    'Sharing an unexpected little event from earlier in the day',
                    'With a philosophical question or quote that lingered with me',
                    'With simple, warm greetings of care and affection',
                ]
            ],
            [
                'dim' => 'communication_style',
                'q' => 'When a friend shares a difficult experience in a letter, what is your primary instinct?',
                'options' => [
                    'Offer quiet emotional presence and compassionate validation',
                    'Share a similar personal memory to help them feel less alone',
                    'Gently ask thoughtful questions to help them reflect',
                    'Provide grounded perspective and encouragement',
                    'Send comfort without overwhelming them with advice',
                ]
            ],
            [
                'dim' => 'communication_style',
                'q' => 'How do you feel about discussing disagreement or differing viewpoints in letters?',
                'options' => [
                    'I welcome respectful philosophical contrast as an intellectual bond',
                    'I prefer exploring shared ground first, touching differences lightly',
                    'I enjoy curious exploration without needing either side to convince the other',
                    'I prefer keeping correspondence focused on personal peace and harmony',
                    'I find differences intriguing if shared with kindness and humility',
                ]
            ],
            [
                'dim' => 'communication_style',
                'q' => 'What role does humor play in your favorite correspondence?',
                'options' => [
                    'Subtle, wry irony woven gently into quiet observations',
                    'Warm, affectionate teasing and silly everyday anecdotes',
                    'Playful wordplay and intellectual puns',
                    'Occasional self-deprecating honesty',
                    'I prefer mostly serious, sincere, and poetic notes',
                ]
            ],

            // Dimension: emotional_openness (6 questions)
            [
                'dim' => 'emotional_openness',
                'q' => 'How readily do you share your inner doubts or vulnerabilities with a new pen friend?',
                'options' => [
                    'Slowly, building trust layer by layer over time',
                    'Comfortably early, as vulnerability fosters true connection',
                    'Primarily through metaphor, art, or stories rather than direct confessions',
                    'When the other person opens that door first with sincerity',
                    'I keep private struggles private and share hopeful perspectives',
                ]
            ],
            [
                'dim' => 'emotional_openness',
                'q' => 'How do you process moments of nostalgia or bittersweet memories?',
                'options' => [
                    'I savor them tenderly; they are the colors of a lived life',
                    'I write them down to understand what they meant to me',
                    'I share them with someone who understands quiet moods',
                    'I feel them briefly and turn back toward the present day',
                    'They make me reflect on how quickly time moves',
                ]
            ],
            [
                'dim' => 'emotional_openness',
                'q' => 'When someone gives you a deeply touching compliment, how do you receive it?',
                'options' => [
                    'Hold it quietly in my heart for days with gratitude',
                    'Feel modest and quickly redirect warmth back to them',
                    'Acknowledge it warmly and appreciate their attentiveness',
                    'Wonder if they see more in me than I see in myself',
                    'Cherish it as a treasured keepsake in my thoughts',
                ]
            ],
            [
                'dim' => 'emotional_openness',
                'q' => 'What kind of emotional atmosphere do you look for in letters?',
                'options' => [
                    'A sanctuary of peace away from modern noise',
                    'A lively campfire where ideas and dreams spark',
                    'A study of deep understanding and candid honesty',
                    'A porch swing of gentle comfort and steady warmth',
                    'A creative workshop of shared curiosities and experiments',
                ]
            ],
            [
                'dim' => 'emotional_openness',
                'q' => 'How comfortable are you expressing sincere affection in written words?',
                'options' => [
                    'Very comfortable; written words give me the courage to be tender',
                    'I show affection through attentive listening and remembering details',
                    'I express it through humor, shared jokes, and loyalty',
                    'I am reserved, choosing words of esteem sparingly and meaningfully',
                    'I grow more expressive as years of correspondence unfold',
                ]
            ],
            [
                'dim' => 'emotional_openness',
                'q' => 'What is your relationship with solitude?',
                'options' => [
                    'It is my essential source of peace, replenishment, and clarity',
                    'I enjoy it in moderation, but love returning to companionship',
                    'I transform it into reading, writing, and creative projects',
                    'I find it bittersweet, both restorative and occasionally lonely',
                    'I navigate between quiet alone time and lively social circles easily',
                ]
            ],

            // Dimension: conversation_depth (6 questions)
            [
                'dim' => 'conversation_depth',
                'q' => 'What type of topic makes you lose track of time while writing?',
                'options' => [
                    'The passage of time, human nature, and memories',
                    'Books, obscure histories, and intriguing philosophy',
                    'Daily observations: morning light, walks, tea, and domestic joys',
                    'Dreams, creative hopes, and unfinished projects',
                    'Social structures, ethics, and world perspectives',
                ]
            ],
            [
                'dim' => 'conversation_depth',
                'q' => 'How do you feel about "small talk" in correspondence?',
                'options' => [
                    'Everyday details are the texture of life if told with attention',
                    'I prefer skipping straight into deeper philosophical questions',
                    'I like a blend: small domestic moments opening into larger thoughts',
                    'I prefer sharing specific passions and recommendations',
                    'I only write when I have something significant to share',
                ]
            ],
            [
                'dim' => 'conversation_depth',
                'q' => 'If a pen friend asks a profound question about your purpose in life, how do you respond?',
                'options' => [
                    'With an honest, unvarnished look at where I am currently searching',
                    'With a thoughtful essay exploring how my perspective has evolved',
                    'With humility, admitting that life is an ongoing mystery',
                    'By recounting pivotal moments that shaped my compass',
                    'With quiet appreciation for the courage to ask such questions',
                ]
            ],
            [
                'dim' => 'conversation_depth',
                'q' => 'What is your ideal balance between asking questions and sharing your own stories?',
                'options' => [
                    'Equal halves: responding to every question and asking thoughtful new ones',
                    'More listening: asking questions and encouraging the other to write',
                    'Storytelling: sharing vivid vignettes and letting questions emerge naturally',
                    'Flow-state: responding freely without counting questions',
                    'Reflective: focusing deeply on one central theme per letter',
                ]
            ],
            [
                'dim' => 'conversation_depth',
                'q' => 'How do you approach exploring complex ethical or moral questions in letters?',
                'options' => [
                    'With deep nuance, avoiding black-and-white simplifications',
                    'Through the lens of personal stories and lived experiences',
                    'By referencing great literature, essays, or historical precedents',
                    'With kindness and an openness to having my own mind changed',
                    'By asking how our everyday choices embody our principles',
                ]
            ],
            [
                'dim' => 'conversation_depth',
                'q' => 'Do you enjoy discussing dreams and subconscious thoughts in letters?',
                'options' => [
                    'Yes, vivid dreams often carry strange, poetic truths',
                    'Occasionally, if they sparked a meaningful waking thought',
                    'I prefer focusing on waking reflections and concrete impressions',
                    'I enjoy analyzing the symbolism behind recurring dreams',
                    'Only when they are whimsical or amusing',
                ]
            ],

            // Dimension: introversion_energy (6 questions)
            [
                'dim' => 'introversion_energy',
                'q' => 'After a week filled with social commitments and public interactions, what do you need most?',
                'options' => [
                    'Complete silence, a warm drink, and a beloved book',
                    'A solitary walk under trees or along an open street',
                    'A quiet letter-writing session to gather my thoughts',
                    'One intimate conversation with a trusted friend',
                    'Sleep, music, and an unstructured day with no obligations',
                ]
            ],
            [
                'dim' => 'introversion_energy',
                'q' => 'How would you characterize your social circle in daily life?',
                'options' => [
                    'A very small, deeply loyal circle of two or three kindred spirits',
                    'A balanced mix of a few close confidants and friendly acquaintances',
                    'Diverse and widespread, though I cherish one-on-one bonds most',
                    'Mostly solitary, finding my closest companions in books and letters',
                    'Warm and community-minded, with an intimate core',
                ]
            ],
            [
                'dim' => 'introversion_energy',
                'q' => 'Why does letter writing appeal to you more than instant messaging?',
                'options' => [
                    'It removes the exhausting pressure of immediate responses',
                    'It allows me to craft thoughts carefully and speak from the heart',
                    'It preserves memories in a tangible, lasting form',
                    'It brings a sense of ceremony, patience, and intimacy',
                    'All of the above in harmonious measure',
                ]
            ],
            [
                'dim' => 'introversion_energy',
                'q' => 'In a crowded room of strangers, where are you most likely to be found?',
                'options' => [
                    'Near the bookshelf, art piece, or quiet corner observing',
                    'Engaged in a focused conversation with just one person',
                    'Near the garden door or balcony breathing fresh air',
                    'Smiling warmly but quietly waiting for an opportune time to leave',
                    'Helping in the kitchen or assisting the host',
                ]
            ],
            [
                'dim' => 'introversion_energy',
                'q' => 'How does your social energy fluctuate throughout the year?',
                'options' => [
                    'I am introspective year-round, blooming during autumn and winter',
                    'Spring and summer bring social energy; autumn invites retreat',
                    'Steady and moderate regardless of seasons',
                    'Cyclical, alternating between periods of expression and hibernation',
                    'Driven by creative projects rather than calendar seasons',
                ]
            ],
            [
                'dim' => 'introversion_energy',
                'q' => 'When you meet someone whose personality is far louder or more extroverted than yours, how do you feel?',
                'options' => [
                    'Entertained from a polite distance, though fatigued if sustained',
                    'Appreciative of their vitality if they leave room for me to breathe',
                    'Fascinated by how differently human beings can navigate the world',
                    'Eager to find their quieter, reflective side',
                    'Comfortable as long as they do not demand I match their volume',
                ]
            ],

            // Dimension: humor (6 questions)
            [
                'dim' => 'humor',
                'q' => 'What kind of comedy brings genuine laughter to you?',
                'options' => [
                    'Witty literary observations and British-style dry humor',
                    'Absurdist, whimsical, and delightfully eccentric situations',
                    'Warm-hearted situational comedy about human foibles',
                    'Sharp satire that cuts through pretension with truth',
                    'Gentle slapstick and wholesome everyday mishaps',
                ]
            ],
            [
                'dim' => 'humor',
                'q' => 'How do you handle awkward or embarrassing moments that happen to you?',
                'options' => [
                    'Turn them into comical anecdotes to entertain friends in letters',
                    'Smile ruefully and accept them as proof of my humanity',
                    'Feel flustered initially, then chuckle about them later',
                    'Analyze how the comedy of errors unfolded',
                    'Treat them with lighthearted grace and move forward',
                ]
            ],
            [
                'dim' => 'humor',
                'q' => 'What is your view on sarcasm in letters?',
                'options' => [
                    'Delightful if gentle and between trusted friends who share it',
                    'I prefer warmth and sincerity over biting remarks',
                    'A sprinkle adds flavor, but too much spoils the soup',
                    'I enjoy intellectual irony, but avoid cynical sarcasm',
                    'Humor should lift up, never belittle',
                ]
            ],
            [
                'dim' => 'humor',
                'q' => 'When a letter contains a witty aside or charming doodle in the margin, what is your reaction?',
                'options' => [
                    'It brightens my whole day and makes the letter unforgettable',
                    'I immediately scheme a clever or playful retort',
                    'I admire the author’s lightness of spirit',
                    'I feel a spark of close companionship',
                    'I appreciate the handwritten personality it conveys',
                ]
            ],
            [
                'dim' => 'humor',
                'q' => 'Can deep sadness and humor coexist in thoughtful correspondence?',
                'options' => [
                    'Inseparable: humor is often how brave hearts endure sadness',
                    'Yes, gentle laughter often brings light into dark corridors',
                    'They must be balanced with great sensitivity and care',
                    'I prefer keeping solemn moments pure and dignified',
                    'The best letters reflect the full bittersweet spectrum of living',
                ]
            ],
            [
                'dim' => 'humor',
                'q' => 'Which of these quotes about laughter resonates most with your worldview?',
                'options' => [
                    '"Laughter is the shortest distance between two people."',
                    '"To truly laugh, you must be able to take your sorrow and play with it."',
                    '"A well-timed joke is a work of high philosophy."',
                    '"The most wasted of all days is one without laughter."',
                    '"There is a quiet smile in simply noticing life go by."',
                ]
            ],

            // Dimension: curiosity_learning (6 questions)
            [
                'dim' => 'curiosity_learning',
                'q' => 'What is your reaction when you encounter an unfamiliar word, concept, or historical reference?',
                'options' => [
                    'I pause immediately to look it up and follow the curiosity rabbit hole',
                    'I write it in a notebook to investigate during quiet hours',
                    'I ask my correspondent to explain their personal connection to it',
                    'I absorb the general meaning from context and keep reading',
                    'I enjoy the mysterious flavor it adds to the page',
                ]
            ],
            [
                'dim' => 'curiosity_learning',
                'q' => 'What field of knowledge outside your primary work or study fascinates you most?',
                'options' => [
                    'Astronomy, cosmology, and the vast night sky',
                    'Ancient history, lost civilizations, and archaeology',
                    'Botany, herbalism, and the secret lives of plants',
                    'Linguistics, etymology, and how languages change',
                    'Architecture, craft heritage, and vernacular building',
                ]
            ],
            [
                'dim' => 'curiosity_learning',
                'q' => 'How do you like to learn new things?',
                'options' => [
                    'Deep, solitary reading of classic books and essays',
                    'Hands-on experimentation and learning through trial',
                    'Thoughtful dialogue with someone knowledgeable and passionate',
                    'Quiet observation of the natural and physical world',
                    'Documentaries, audio lectures, and visiting historic places',
                ]
            ],
            [
                'dim' => 'curiosity_learning',
                'q' => 'If you could spend a month learning one traditional craft from a master, which would you pick?',
                'options' => [
                    'Traditional bookbinding and paper marbling',
                    'Fine woodworking or carpentry with hand tools',
                    'Wheel-thrown ceramics and stoneware pottery',
                    'Botanical ink-making and calligraphy',
                    'Darkroom analog photography and hand-printing',
                ]
            ],
            [
                'dim' => 'curiosity_learning',
                'q' => 'How do you feel when a pen friend introduces you to an obscure passion of theirs?',
                'options' => [
                    'Thrilled: passionate people make any subject captivating',
                    'Curious to see what philosophical lessons apply to my own life',
                    'Honored that they shared their unique world with me',
                    'Eager to try understanding it through their eyes',
                    'Appreciative of the novelty and fresh perspective',
                ]
            ],
            [
                'dim' => 'curiosity_learning',
                'q' => 'What is the most rewarding kind of conversation about ideas?',
                'options' => [
                    'When both people discover an insight neither had before the discussion',
                    'When complex thoughts are expressed with crystalline simplicity',
                    'When personal anecdotes illuminate an abstract truth',
                    'When assumptions are gently questioned with mutual kindness',
                    'When ideas inspire real-world creative action',
                ]
            ],

            // Dimension: music_arts (6 questions)
            [
                'dim' => 'music_arts',
                'q' => 'What musical mood best accompanies your writing or reading hours?',
                'options' => [
                    'Classical piano (Chopin, Debussy, Satie)',
                    'Vintage folk, acoustic guitar, or traditional ballads',
                    'Analog ambient, lo-fi, or quiet orchestral soundscapes',
                    '1980s soft synth, post-punk, or nostalgic indie',
                    'Natural silence: rain on the glass, wind in the eaves',
                ]
            ],
            [
                'dim' => 'music_arts',
                'q' => 'How does visual art impact your spirit?',
                'options' => [
                    'Deeply: impressionist light, sketches, and paintings stir my imagination',
                    'I find poetry in everyday visual textures: worn wood, stone, shadows',
                    'I appreciate museum visits as pilgrimages of contemplation',
                    'I love postcards and miniature illustrations tucked into envelopes',
                    'I am more moved by melodies and words than visual canvases',
                ]
            ],
            [
                'dim' => 'music_arts',
                'q' => 'Do you enjoy exchanging music recommendations with friends?',
                'options' => [
                    'Enormously: sharing a song is sharing an emotional world',
                    'I love hearing the specific memory attached to a recommended track',
                    'I am selective, but treasure rare, thoughtful musical gifts',
                    'I prefer exploring music on my own quiet journeys',
                    'I enjoy reading lyrics and poems more than listening to songs',
                ]
            ],
            [
                'dim' => 'music_arts',
                'q' => 'When visiting an art gallery or historical museum, what is your rhythm?',
                'options' => [
                    'Slowly sitting before two or three favorite works for an hour',
                    'Wandering quietly through the rooms absorbing overall atmosphere',
                    'Reading every plaque and discovering the stories behind artifacts',
                    'Sketching or jotting notes in a pocket journal',
                    'Finding the courtyard garden or quiet cafe to reflect',
                ]
            ],
            [
                'dim' => 'music_arts',
                'q' => 'What aesthetic movement or visual era speaks most warmly to your taste?',
                'options' => [
                    'Arts and Crafts movement (William Morris, handmade integrity)',
                    'Impressionism (capturing fleeting light and natural color)',
                    'Vintage 1970s/80s analog warmth (Polaroids, film grain, corduroy)',
                    'Japanese Wabi-Sabi (rustic simplicity, beauty in imperfection)',
                    'Romanticism (sublime nature, dramatic ruins, deep sentiment)',
                ]
            ],
            [
                'dim' => 'music_arts',
                'q' => 'If you could own one original piece of creative heritage, what would it be?',
                'options' => [
                    'A handwritten letter by a beloved historical author or poet',
                    'An original landscape watercolor from the 19th century',
                    'A vintage, beautifully maintained mechanical typewriter',
                    'A collection of rare 35mm film photographs from forgotten journeys',
                    'A hand-bound blank leather journal made with handmade cotton rag paper',
                ]
            ],

            // Dimension: reading_writing (6 questions)
            [
                'dim' => 'reading_writing',
                'q' => 'What genre of books has held your loyalty throughout your life?',
                'options' => [
                    'Classic literature and 19th/20th century novels',
                    'Memoirs, personal essays, and epistolary collections',
                    'Historical fiction, gentle mysteries, and period dramas',
                    'Philosophy, nature writing, and contemplative non-fiction',
                    'Poetry, folk tales, and atmospheric magical realism',
                ]
            ],
            [
                'dim' => 'reading_writing',
                'q' => 'Where is your favorite place in the world to read?',
                'options' => [
                    'An armchair by a sunlit window with a blanket and tea',
                    'A corner bench in an old wood-paneled library',
                    'Outdoors under a leafy tree on a breezy summer day',
                    'In bed at night with a gentle reading lamp while the world sleeps',
                    'On a long train journey gazing out at rolling landscapes',
                ]
            ],
            [
                'dim' => 'reading_writing',
                'q' => 'How do you treat the physical books you own?',
                'options' => [
                    'With tender reverence: uncreased spines and acid-free bookmarks',
                    'Well-loved: pencil marginalia, dog-eared epiphanies, and pressed flowers',
                    'Collected like old companions on crowded wooden shelves',
                    'Passed freely to friends who might need their wisdom',
                    'Browsed in secondhand bookshops with the scent of aged paper',
                ]
            ],
            [
                'dim' => 'reading_writing',
                'q' => 'What writing instrument brings you the greatest tactile joy?',
                'options' => [
                    'A fountain pen with sepia, burgundy, or forest-green ink',
                    'A well-balanced rollerball or fine gel pen gliding smoothly',
                    'An HB wooden pencil with a clean graphite stroke',
                    'The rhythmic clatter of a manual typewriter on crisp bond paper',
                    'The clear, peaceful digital typography of a quiet screen',
                ]
            ],
            [
                'dim' => 'reading_writing',
                'q' => 'Do you keep a personal journal or notebook?',
                'options' => [
                    'Yes, a daily or weekly ritual of recording thoughts and moments',
                    'A commonplace book collecting quotes, snippets, and observations',
                    'Sporadically, opening the pages only during intense emotional periods',
                    'My correspondence with friends serves as my living journal',
                    'I prefer keeping thoughts in the treasury of memory',
                ]
            ],
            [
                'dim' => 'reading_writing',
                'q' => 'Which element of a story matters most to you?',
                'options' => [
                    'Atmosphere and the evocative music of the prose',
                    'Psychological depth and authentic character relationships',
                    'A compelling mystery or moral question to ponder',
                    'A vivid sense of place and historical texture',
                    'The emotional resonance that lingers long after closing the book',
                ]
            ],

            // Dimension: travel_exploration (6 questions)
            [
                'dim' => 'travel_exploration',
                'q' => 'What is your philosophy of traveling to a new city or country?',
                'options' => [
                    'Get pleasantly lost on side streets, visiting neighborhood bakers and cafes',
                    'Seek out the oldest libraries, antiquarian shops, and historical gardens',
                    'Walk everywhere on foot from morning until dusk taking in the rhythm',
                    'Sit in public squares sketching or writing letters to friends back home',
                    'Connect with local craftspeople and experience everyday life',
                ]
            ],
            [
                'dim' => 'travel_exploration',
                'q' => 'Which landscape brings you the greatest feeling of peace?',
                'options' => [
                    'Mist-covered coastal cliffs overlooking a stormy grey ocean',
                    'Ancient mossy forests with towering pines and running brooks',
                    'Rolling green countryside dotted with old stone cottages',
                    'Quiet mountain valleys bathed in crisp, clean alpine air',
                    'Historic cobblestone villages with slate roofs and chimneys',
                ]
            ],
            [
                'dim' => 'travel_exploration',
                'q' => 'What do you typically bring home as a keepsake from a journey?',
                'options' => [
                    'Postcards, vintage stamps, or ticket stubs tucked into a journal',
                    'A small smooth pebble, pinecone, or pressed leaf from a walk',
                    'A locally roasted tea or spice blend that evokes the locale',
                    'A handmade craft or ceramic cup used for morning coffee',
                    'A secondhand book found in a local shop in that region’s language',
                ]
            ],
            [
                'dim' => 'travel_exploration',
                'q' => 'How do you feel about traveling completely alone?',
                'options' => [
                    'It is one of the most liberating, self-reflective gifts in the world',
                    'I cherish it, but always long to share discoveries with a friend via letters',
                    'I prefer traveling with one calm, compatible companion',
                    'I enjoy the balance of solitary days and friendly hostel or cafe talks',
                    'I prefer exploring my own immediate province and staying near home',
                ]
            ],
            [
                'dim' => 'travel_exploration',
                'q' => 'What mode of travel appeals most to your romantic imagination?',
                'options' => [
                    'A sleeper train journey across rolling continental borders',
                    'A walking pilgrimage along an ancient rural trail',
                    'A slow ferry across misty islands and harbor towns',
                    'A meandering bicycle ride through quiet river valleys',
                    'An armchair journey through letters and travel memoirs',
                ]
            ],
            [
                'dim' => 'travel_exploration',
                'q' => 'When describing a place in a letter, what details do you focus on?',
                'options' => [
                    'The scent of air, quality of light, and sounds of the evening',
                    'The faces, dress, and casual conversations of passing people',
                    'The architectural age of the walls, doorways, and masonry',
                    'My own internal feelings and reflections while standing there',
                    'The contrast between expectations and raw reality',
                ]
            ],

            // Dimension: nature_outdoors (6 questions)
            [
                'dim' => 'nature_outdoors',
                'q' => 'How does time spent in the natural world affect your mood?',
                'options' => [
                    'It is my sanctuary: it instantly restores my nervous system and sanity',
                    'It inspires wonder, creativity, and gratitude for quiet life',
                    'It reminds me of the seasonal cycles and puts human worries in scale',
                    'I enjoy it as a pleasant backdrop for quiet walks and thinking',
                    'I love bringing cuttings, leaves, and botanical sketches indoors',
                ]
            ],
            [
                'dim' => 'nature_outdoors',
                'q' => 'Which season do you feel most spiritually aligned with?',
                'options' => [
                    'Autumn: golden leaves, crisp air, woodsmoke, and warm wool',
                    'Winter: still landscapes, quiet nights, snowfall, and candle fire',
                    'Spring: green shoots, morning birdsong, blossoming trees, and renewal',
                    'Summer: long twilight evenings, wildflowers, and warm open waters',
                    'The fleeting transition weeks between the major seasons',
                ]
            ],
            [
                'dim' => 'nature_outdoors',
                'q' => 'What is your relationship with gardening or houseplants?',
                'options' => [
                    'I tend a quiet jungle of pots, talking to leaves and checking new shoots',
                    'I dream of an overgrown cottage garden with herbs and lavender',
                    'I admire nature in the wild rather than cultivating it indoors',
                    'I keep a few resilient green companions on my desk for companionship',
                    'I love foraging edible plants, mushrooms, and seasonal berries',
                ]
            ],
            [
                'dim' => 'nature_outdoors',
                'q' => 'How do you react to a sudden, gentle afternoon rainstorm?',
                'options' => [
                    'Step onto the porch to breathe in the petrichor and watch drops fall',
                    'Make a warm pot of tea and curl up with paper to write a letter',
                    'Open the windows wide so the damp breeze fills the rooms',
                    'Walk outside under an umbrella listening to the sound on canvas',
                    'Savor the quiet excuse to slow down and stay indoors without guilt',
                ]
            ],
            [
                'dim' => 'nature_outdoors',
                'q' => 'Which animal companion or wild creature brings you a smile when spotted?',
                'options' => [
                    'A quiet, thoughtful cat curled peacefully by a fireplace',
                    'Songbirds visiting a feeder or bathing in a stone bowl',
                    'A loyal, gentle dog trotting along country paths',
                    'Wild foxes, deer, or nocturnal creatures slipping through woods',
                    'Frogs and garden creatures thriving in small quiet corners',
                ]
            ],
            [
                'dim' => 'nature_outdoors',
                'q' => 'If you had a cabin in the wilderness for three months, what would you bring?',
                'options' => [
                    'Fifty chosen books, abundant paper, ink, and a woodstove kettle',
                    'Painting supplies, botanical field guides, and a telescope',
                    'Warm blankets, hiking boots, and ingredients for baking bread',
                    'A notebook, a radio for evening news, and gardening seeds',
                    'Tools for woodworking, carving, and maintaining the hearth',
                ]
            ],

            // Dimension: daily_routines (6 questions)
            [
                'dim' => 'daily_routines',
                'q' => 'What does your ideal morning look like?',
                'options' => [
                    'Waking early before the world stirs; silence, hot coffee, and writing',
                    'Gentle awakening with soft music, a nourishing breakfast, and daylight',
                    'A brisk morning walk outside followed by an organized plan for the day',
                    'A slow, leisurely stretch of reading in bed with no urgent alarm',
                    'Every morning brings its own rhythm depending on the week',
                ]
            ],
            [
                'dim' => 'daily_routines',
                'q' => 'What is your evening ritual for unwinding?',
                'options' => [
                    'Lighting a candle, steeping herbal tea, and reading or letter writing',
                    'Listening to records or soothing music in dim, warm lamplight',
                    'A warm bath, quiet contemplation, and early sleep',
                    'Reflecting in my journal and organizing my desk for tomorrow',
                    'A calm conversation with someone I care about',
                ]
            ],
            [
                'dim' => 'daily_routines',
                'q' => 'How do you feel about predictability versus spontaneity in life?',
                'options' => [
                    'I thrive on quiet, comforting routines that anchor my peace of mind',
                    'I like a sturdy daily routine, with weekends open to gentle whim',
                    'I prefer living organically, letting each day dictate its own direction',
                    'I find too much routine stifling; novelty keeps my mind alert',
                    'Routines are my canvas upon which creative spontaneity can safely occur',
                ]
            ],
            [
                'dim' => 'daily_routines',
                'q' => 'What hot beverage holds the title of your daily companion?',
                'options' => [
                    'Black tea (Earl Grey, English Breakfast, Assam) with milk or honey',
                    'Green tea, matcha, or delicate white teas steeped with attention',
                    'Dark, rich artisanal coffee brewed slowly in a French press or pour-over',
                    'Herbal infusions (chamomile, peppermint, rooibos) throughout the day',
                    'Hot spiced cider, cocoa, or comforting warm milk with nutmeg',
                ]
            ],
            [
                'dim' => 'daily_routines',
                'q' => 'How organized is your personal desk or writing space?',
                'options' => [
                    'A tidy, curated sanctuary: clean blotter, pens in jars, neat paper',
                    'An affectionate creative clutter: stacks of books, postcards, and notes',
                    'Minimalist and bare: only the immediate project at hand',
                    'A traveling desk: I write wherever a comfortable flat surface appears',
                    'Organized chaos that makes perfect sense only to me',
                ]
            ],
            [
                'dim' => 'daily_routines',
                'q' => 'How do you celebrate completing a meaningful project or task?',
                'options' => [
                    'A long quiet evening doing absolutely nothing with a clear conscience',
                    'Treating myself to a special pastry, fine meal, or good wine',
                    'Buying a coveted book or art supply I have had my eye on',
                    'Taking a celebratory walk in nature to let my mind breathe',
                    'Writing a letter to a friend sharing the satisfaction of the milestone',
                ]
            ],

            // Dimension: personal_values (6 questions)
            [
                'dim' => 'personal_values',
                'q' => 'Which virtue do you admire most in a fellow human being?',
                'options' => [
                    'Kindness and gentle consideration for the vulnerable',
                    'Integrity: doing what is right even when unobserved',
                    'Humility: quiet competence without the need to boast',
                    'Courage: speaking the truth and standing by one’s values',
                    'Empathy: the ability to step fully into another’s shoes',
                ]
            ],
            [
                'dim' => 'personal_values',
                'q' => 'What is your view on the relentless pace of modern digital technology?',
                'options' => [
                    'I intentionally resist it: slow living, analog craft, and quiet depths',
                    'I use it as a tool, but protect sacred boundaries around my private life',
                    'It has created widespread disconnection despite surface connectivity',
                    'I appreciate its practical benefits while seeking nostalgic antidotes',
                    'I cherish platforms like Dearly that repurpose digital tools for slow intimacy',
                ]
            ],
            [
                'dim' => 'personal_values',
                'q' => 'How do you measure a well-lived life?',
                'options' => [
                    'By the depth of love given and the warmth shared with others',
                    'By having lived authentically according to one’s own inner compass',
                    'By cultivating curiosity, wisdom, and leaving beauty behind',
                    'By finding peace in simple, daily, unremarkable moments',
                    'By easing the burdens of those with whom we share this world',
                ]
            ],
            [
                'dim' => 'personal_values',
                'q' => 'When faced with an ethical dilemma where two good things conflict, how do you choose?',
                'options' => [
                    'Choose the path of greater compassion and least harm to human hearts',
                    'Adhere to fundamental principles of honesty and justice',
                    'Listen to the quiet voice of conscience after deep meditation',
                    'Seek counsel from wise friends and enduring books of wisdom',
                    'Consider the long-term ripple effects across generations',
                ]
            ],
            [
                'dim' => 'personal_values',
                'q' => 'What is your attitude toward material possessions?',
                'options' => [
                    'Fewer, better things: cherishing well-crafted items that age gracefully',
                    'Sentimental attachment to objects filled with history and memories',
                    'Simplicity and detachment: freedom from the burden of accumulating goods',
                    'Practical utility: things are tools to facilitate life and creativity',
                    'Loving books, art, and stationery above all other material luxuries',
                ]
            ],
            [
                'dim' => 'personal_values',
                'q' => 'How important is lifelong personal growth and learning to you?',
                'options' => [
                    'Fundamental: a human being should never stop growing in soul and mind',
                    'I value softening with age: becoming more forgiving, patient, and kind',
                    'I love acquiring new skills, languages, and perspectives continuously',
                    'I value deepening my understanding of life rather than chasing ambition',
                    'Growth happens naturally if we remain open and humble to experience',
                ]
            ],

            // Dimension: friendship_expectations (6 questions)
            [
                'dim' => 'friendship_expectations',
                'q' => 'What quality do you prize above all else in a pen friend?',
                'options' => [
                    'Thoughtful sincerity: writing what they truly feel without pretense',
                    'Patience: understanding that life has seasons and not rushing replies',
                    'Curiosity: an eager interest in exchanging ideas and life perspectives',
                    'Warmth: making the other person feel heard, remembered, and valued',
                    'Consistency: a steady, dependable presence across months and years',
                ]
            ],
            [
                'dim' => 'friendship_expectations',
                'q' => 'If several weeks or months pass before a pen friend replies, what is your mindset?',
                'options' => [
                    'Total understanding: genuine letters require peace, not deadlines',
                    'A gentle hope that all is well with them, ready to welcome their return',
                    'I hold no resentment whatsoever; letters find their way in due time',
                    'I assume life grew busy and will gladly pick up where we left off',
                    'I might send a brief, pressure-free postcard of warm thinking of you',
                ]
            ],
            [
                'dim' => 'friendship_expectations',
                'q' => 'How do you feel about exchanging little physical mementos (pressed flowers, postcards)?',
                'options' => [
                    'I love digital and analog tokens: stamps, drawings, and small touches',
                    'A beautiful, well-crafted letter is the ultimate gift in itself',
                    'I cherish thoughtful recommendations and shared passages most of all',
                    'Small aesthetic gestures make correspondence feel alive and tactile',
                    'I focus on the written thought, though charming details are delightful',
                ]
            ],
            [
                'dim' => 'friendship_expectations',
                'q' => 'Do you envision pen friendships remaining purely epistolary or meeting one day?',
                'options' => [
                    'I love the unique magic of letters; keeping it epistolary is romantic',
                    'If years of letters build a true bond, meeting over tea would be lovely',
                    'I take it as it unfolds naturally with zero expectations',
                    'Letters allow a purity of soul that physical meetings sometimes complicate',
                    'A lifelong correspondence across continents is a beautiful goal',
                ]
            ],
            [
                'dim' => 'friendship_expectations',
                'q' => 'What is the greatest reward of having a dedicated pen friend?',
                'options' => [
                    'A dedicated space in my life where slow, deep reflection is welcomed',
                    'Gaining a window into another life, culture, and way of seeing the world',
                    'Knowing someone out there in the world is holding space for my thoughts',
                    'The joyful anticipation of opening the mailbox and finding a sealed letter',
                    'Growing together as wiser, kinder people through written fellowship',
                ]
            ],
            [
                'dim' => 'friendship_expectations',
                'q' => 'How do you handle sharing joy or success without sounding like you are boasting?',
                'options' => [
                    'Share the gratitude and humble wonder behind the good fortune',
                    'Describe the relief and lessons learned during the hard work to get there',
                    'Share it openly, knowing a true friend delights in your happiness',
                    'Frame it alongside affectionate encouragement for their own endeavors',
                    'Keep it modest and let my friend ask if they wish to know more',
                ]
            ],

            // Dimension: response_frequency (5 questions)
            [
                'dim' => 'response_frequency',
                'q' => 'What correspondence rhythm feels most sustainable for your lifestyle?',
                'options' => [
                    'One rich, thoughtful letter every two to three weeks',
                    'A steady monthly letter like a beloved magazine issue',
                    'Frequent shorter letters every week or ten days',
                    'A seasonal exchange: four to six magnificent letters per year',
                    'Completely organic: whenever life presents a pause for the pen',
                ]
            ],
            [
                'dim' => 'response_frequency',
                'q' => 'When a notification arrives that a new letter has reached your mailbox, what do you do?',
                'options' => [
                    'Wait for a quiet evening, light a candle, and savor opening it slowly',
                    'Open it immediately with eager anticipation and read every line',
                    'Read it through once quickly, then re-read it later with my pen ready',
                    'Save it for the weekend as a special reward',
                    'Let the anticipation build for a day before breaking the seal',
                ]
            ],
            [
                'dim' => 'response_frequency',
                'q' => 'How many active pen friends can you comfortably maintain at one time?',
                'options' => [
                    'One or two: I prefer deep, undivided correspondence',
                    'Three to four: a diverse, balanced circle of kindred spirits',
                    'A small constellation of five to six correspondents',
                    'I prefer quality over quantity, focusing on one soul at a time',
                    'I write when moved to write, regardless of the number of recipients',
                ]
            ],
            [
                'dim' => 'response_frequency',
                'q' => 'If you know you will be traveling or unable to write for an extended period, what is your approach?',
                'options' => [
                    'Send a quick courtesy note letting my friend know I will be away',
                    'Share news of the adventure in a grand return letter once settled',
                    'Trust that true pen friends understand pauses without needing explanations',
                    'Write drafts on the road to send all at once upon my return',
                    'Mention it in the preceding letter if anticipated',
                ]
            ],
            [
                'dim' => 'response_frequency',
                'q' => 'What is your reaction if a pen friend asks to slow down the pace of correspondence?',
                'options' => [
                    'Completely welcome it: slow, pressure-free letters are the truest letters',
                    'Appreciate their honesty and adjust happily to their preferred cadence',
                    'Reassure them with warmth that their well-being comes first',
                    'Gladly agree; I often appreciate more breathing room myself',
                    'Celebrate that our friendship is mature enough to speak openly',
                ]
            ],

            // Dimension: creative_interests (6 questions)
            [
                'dim' => 'creative_interests',
                'q' => 'Which creative hobby gives you the greatest sense of calm flow?',
                'options' => [
                    'Writing stories, essays, or personal memoirs',
                    'Painting, sketching, watercolor, or calligraphy',
                    'Cooking, baking bread, or crafting artisanal preserves',
                    'Knitting, sewing, embroidery, or textile mending',
                    'Photography, collecting vintage prints, or scrapbooking',
                ]
            ],
            [
                'dim' => 'creative_interests',
                'q' => 'How do you express your creativity in personal correspondence?',
                'options' => [
                    'Choosing exquisite stationery themes, stamps, and digital wax seals',
                    'Crafting vivid sensory descriptions and evocative metaphors',
                    'Including little marginalia, quotes, and philosophical reflections',
                    'Tailoring every greeting and sign-off with personalized care',
                    'The composition of a heartfelt letter is an art form in itself',
                ]
            ],
            [
                'dim' => 'creative_interests',
                'q' => 'What is your relationship with unfinished creative projects?',
                'options' => [
                    'A drawer of gentle drafts waiting for their rightful season',
                    'I finish what I start with patient, methodical persistence',
                    'The joy is in the process of creating, not merely the finished product',
                    'I revisit them after months away and discover fresh sparks',
                    'They are stepping stones that led to whatever I am making today',
                ]
            ],
            [
                'dim' => 'creative_interests',
                'q' => 'If you had an uninterrupted rainy Saturday with no obligations, what would you make?',
                'options' => [
                    'A loaf of crusty sourdough bread and a warm berry pie',
                    'A watercolor study of a branch or teacup on heavy paper',
                    'Three long, detailed letters to pen friends across the world',
                    'A curated playlist or mixtape capturing a particular nostalgic mood',
                    'A hand-bound booklet or decorated collection of beloved quotes',
                ]
            ],
            [
                'dim' => 'creative_interests',
                'q' => 'How do you handle creative dry spells or writer’s block?',
                'options' => [
                    'Take long walks without a phone, letting silence refill the well',
                    'Read classic literature and poetry to soak in masterly language',
                    'Switch to tactile crafts like cooking or cleaning my desk',
                    'Accept it with patience: winter is as necessary as springtime',
                    'Write through it anyway, even if just describing the room around me',
                ]
            ],
            [
                'dim' => 'creative_interests',
                'q' => 'Do you enjoy receiving hand-drawn sketches or creative flourishes inside letters?',
                'options' => [
                    'It is one of the most delightful surprises a letter can hold',
                    'I treasure them and keep them safe between the pages',
                    'I love seeing the personal hand of the author come alive',
                    'It inspires me to add my own creative touches in reply',
                    'It makes the letter feel like a timeless collectible artifact',
                ]
            ],

            // Dimension: cultural_interests (6 questions)
            [
                'dim' => 'cultural_interests',
                'q' => 'How do you feel about exchanging letters with someone from a completely different country or culture?',
                'options' => [
                    'It is one of the greatest joys in life: building bridges of mutual understanding',
                    'I love learning about the daily routines, festivals, and weather of far-off lands',
                    'I marvel at how universal human emotions are across all cultural differences',
                    'I am eager to share my own local traditions with humility and respect',
                    'It makes the globe feel smaller, warmer, and more intimately connected',
                ]
            ],
            [
                'dim' => 'cultural_interests',
                'q' => 'What aspect of other cultures do you most enjoy exploring?',
                'options' => [
                    'Culinary traditions, family recipes, and the rituals of tea and meals',
                    'Traditional folklore, mythologies, and regional storytelling',
                    'Artistic styles, historical architecture, and textile patterns',
                    'Idioms, unique proverbs, and words that have no direct translation',
                    'The history of everyday life and how communities maintained connection',
                ]
            ],
            [
                'dim' => 'cultural_interests',
                'q' => 'If your pen friend shares a proverb from their native language, how do you respond?',
                'options' => [
                    'Ponder its wisdom and see how it reflects their worldview',
                    'Share a corresponding proverb or saying from my own background',
                    'Write it down in my notebook as a treasured linguistic gem',
                    'Ask about the historical or cultural story behind the saying',
                    'Reflect on how timeless truths echo in different languages',
                ]
            ],
            [
                'dim' => 'cultural_interests',
                'q' => 'What is your view on exploring historical eras through correspondence?',
                'options' => [
                    'I am fascinated by how people lived, thought, and loved in the 19th/20th century',
                    'Letters are time machines: they reveal historical truth better than textbooks',
                    'I love the dignified politeness and eloquence of historic letter writers',
                    'I enjoy comparing our modern anxieties with those of past generations',
                    'I find comfort in knowing humanity has endured through every century',
                ]
            ],
            [
                'dim' => 'cultural_interests',
                'q' => 'How do you approach learning about international holidays and seasons?',
                'options' => [
                    'With warm curiosity, asking my friend how their family celebrates',
                    'By wishing them well and learning the cultural significance of the day',
                    'By sharing how corresponding seasons feel in my hemisphere',
                    'By appreciating the beautiful diversity of human celebration',
                    'By reflecting on the shared human desire for light, joy, and feast',
                ]
            ],
            [
                'dim' => 'cultural_interests',
                'q' => 'What kind of cultural exchanges feel most authentic to you?',
                'options' => [
                    'Sharing the modest reality of everyday domestic life, not just tourist sights',
                    'Exchanging descriptions of neighborhood markets, old bakeries, and streets',
                    'Discussing childhood memories and the songs sung by grandparents',
                    'Recommending beloved local authors, poets, and filmmakers',
                    'Talking about the local flora, fauna, and changing seasons of our regions',
                ]
            ],

            // Dimension: long_term_correspondence (6 questions)
            [
                'dim' => 'long_term_correspondence',
                'q' => 'What is your hope for your correspondence on Dearly over the coming years?',
                'options' => [
                    'To nurture one or two lifelong pen friendships that span decades',
                    'To maintain a steady, comforting postal sanctuary I can always return to',
                    'To leave behind a chronicle of growth, friendship, and quiet truth',
                    'To connect with kindred spirits who appreciate the lost art of the letter',
                    'To experience the joy of finding letters waiting in my postbox',
                ]
            ],
            [
                'dim' => 'long_term_correspondence',
                'q' => 'How do you feel when looking back at letters you wrote or received years ago?',
                'options' => [
                    'Overwhelmed with gratitude for the people who walked alongside me',
                    'Fascinated by how much I have grown while my core remains the same',
                    'Tender toward the younger self who wrote those earnest words',
                    'Reminded that words written with love never lose their warmth',
                    'Inspired to continue writing to ensure today’s moments are not lost',
                ]
            ],
            [
                'dim' => 'long_term_correspondence',
                'q' => 'What makes a friendship capable of surviving across time and distance?',
                'options' => [
                    'Mutual grace: expecting no perfection, only sincere affection and patience',
                    'Shared values and an appreciation for each other’s inner life',
                    'The understanding that silence between letters does not mean absence of care',
                    'Loyalty that picks up immediately even after long pauses',
                    'Honesty that can withstand life’s seasons and changing circumstances',
                ]
            ],
            [
                'dim' => 'long_term_correspondence',
                'q' => 'How do you handle periods when your life becomes overwhelmingly busy?',
                'options' => [
                    'I pause correspondence peacefully, knowing my friends will wait with grace',
                    'I send a small, affectionate note acknowledging the busy season',
                    'Writing becomes my haven where I escape the busyness to breathe',
                    'I focus on immediate responsibilities, then return with a grand catch-up letter',
                    'I reflect on how to simplify my life and reclaim time for letters',
                ]
            ],
            [
                'dim' => 'long_term_correspondence',
                'q' => 'What is your perspective on growing older as a correspondent?',
                'options' => [
                    'Letters become richer with age, seasoned with experience and gentleness',
                    'I look forward to reminiscing with long-term pen friends across decades',
                    'Age brings an economy of words and a deeper capacity for true listening',
                    'It is a privilege to share the seasons of life with someone through ink',
                    'Every passing year makes genuine friendship more precious',
                ]
            ],
            [
                'dim' => 'long_term_correspondence',
                'q' => 'If you could send a letter to yourself ten years in the future, what would you ask?',
                'options' => [
                    '"Did you remember to protect your peace and live with a gentle heart?"',
                    '"Are you still writing letters to the kindred spirits you found?"',
                    '"What small everyday joys are filling your mornings now?"',
                    '"Did you build the quiet life filled with books and nature you dreamed of?"',
                    '"I hope you are smiling, surrounded by love and beloved memories."',
                ]
            ],
        ];

        // 3. Insert all questions into the database and attach them to questionnaire lengths
        $questionModels = [];
        foreach ($rawQuestions as $idx => $qData) {
            $question = Question::updateOrCreate(
                [
                    'dimension' => $qData['dim'],
                    'question_text' => $qData['q'],
                ],
                [
                    'weight' => 1.00,
                    'is_active' => true,
                    'order_index' => $idx + 1,
                ]
            );

            // Add options A through E with score values 1 to 5
            $keys = ['A', 'B', 'C', 'D', 'E'];
            foreach ($qData['options'] as $optIdx => $optText) {
                QuestionOption::updateOrCreate(
                    [
                        'question_id' => $question->id,
                        'option_key' => $keys[$optIdx],
                    ],
                    [
                        'option_text' => $optText,
                        'score_value' => $optIdx + 1,
                        'order_index' => $optIdx + 1,
                    ]
                );
            }

            $questionModels[] = $question;
        }

        // Attach questions to the respective questionnaires:
        // 20-length gets first 20
        // 30-length gets first 30
        // 40-length gets first 40
        // 50-length gets first 50
        // (The total bank has 100+ questions available for admin rotation and future versions)
        foreach ($lengths as $len) {
            $qObj = $questionnaires[$len];
            // Assign questionnaire_id for the primary version
            for ($i = 0; $i < $len && $i < count($questionModels); $i++) {
                // If question doesn't have a questionnaire assigned or is for this one
                if (!$questionModels[$i]->questionnaire_id) {
                    $questionModels[$i]->update(['questionnaire_id' => $qObj->id]);
                }
            }
        }
    }
}
