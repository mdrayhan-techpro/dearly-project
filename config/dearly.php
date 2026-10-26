<?php

return [
    'name' => 'Dearly',
    'tagline' => 'Letters Find Their Way.',

    'default_match_threshold' => env('DEARLY_DEFAULT_MATCH_THRESHOLD', 90),
    'default_max_letters_per_day' => env('DEARLY_DEFAULT_MAX_LETTERS_PER_DAY', 5),
    'default_max_first_contacts_per_day' => env('DEARLY_DEFAULT_MAX_FIRST_CONTACTS_PER_DAY', 2),

    'supported_locales' => [
        'en' => ['name' => 'English', 'native' => 'English', 'dir' => 'ltr'],
        'bn' => ['name' => 'Bengali', 'native' => 'বাংলা', 'dir' => 'ltr'],
        'hi' => ['name' => 'Hindi', 'native' => 'हिन्दी', 'dir' => 'ltr'],
        'ar' => ['name' => 'Arabic', 'native' => 'العربية', 'dir' => 'rtl'],
        'es' => ['name' => 'Spanish', 'native' => 'Español', 'dir' => 'ltr'],
        'fr' => ['name' => 'French', 'native' => 'Français', 'dir' => 'ltr'],
        'pt' => ['name' => 'Portuguese', 'native' => 'Português', 'dir' => 'ltr'],
    ],

    'dimensions' => [
        'communication_style' => 'Communication Style',
        'emotional_openness' => 'Emotional Openness',
        'conversation_depth' => 'Preferred Conversation Depth',
        'introversion_energy' => 'Introversion & Social Energy',
        'humor' => 'Humor & Playfulness',
        'curiosity_learning' => 'Curiosity & Learning',
        'music_arts' => 'Music & Arts',
        'reading_writing' => 'Reading & Writing',
        'travel_exploration' => 'Travel & Exploration',
        'nature_outdoors' => 'Nature & Outdoor Activities',
        'daily_routines' => 'Daily Routines',
        'personal_values' => 'Personal Values',
        'friendship_expectations' => 'Friendship Expectations',
        'response_frequency' => 'Preferred Response Frequency',
        'creative_interests' => 'Creative Interests',
        'cultural_interests' => 'Cultural Interests',
        'long_term_correspondence' => 'Long-Term Correspondence Preferences',
    ],
];
