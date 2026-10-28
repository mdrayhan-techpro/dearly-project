<?php

namespace Database\Seeders;

use App\Models\ApplicationSetting;
use Illuminate\Database\Seeder;

class ApplicationSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Core Platform Settings
            ['key' => 'site_name', 'value' => 'Dearly', 'type' => 'string'],
            ['key' => 'site_tagline', 'value' => 'Letters Find Their Way.', 'type' => 'string'],
            ['key' => 'match_threshold', 'value' => '90', 'type' => 'integer'],
            ['key' => 'max_letters_per_day', 'value' => '5', 'type' => 'integer'],
            ['key' => 'max_first_contacts_per_day', 'value' => '2', 'type' => 'integer'],
            ['key' => 'default_language', 'value' => 'en', 'type' => 'string'],
            ['key' => 'contact_email', 'value' => 'postmaster@dearlyletters.com', 'type' => 'string'],
            ['key' => 'registration_enabled', 'value' => '1', 'type' => 'boolean'],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean'],
            ['key' => 'brand_color_ivory', 'value' => '#F6F0E5', 'type' => 'string'],
            ['key' => 'brand_color_burgundy', 'value' => '#783C46', 'type' => 'string'],
            ['key' => 'brand_color_ink', 'value' => '#342A25', 'type' => 'string'],
            ['key' => 'brand_color_brass', 'value' => '#B79A61', 'type' => 'string'],
            ['key' => 'brand_color_forest', 'value' => '#405B4B', 'type' => 'string'],

            // ==========================================
            // ABOUT PAGE CONTENT (MULTILINGUAL)
            // ==========================================
            ['key' => 'about_title_en', 'value' => 'About the Dearly Post Office', 'type' => 'string'],
            ['key' => 'about_title_bn', 'value' => 'ডিয়ারলি ডাকঘর পরিচিতি', 'type' => 'string'],
            ['key' => 'about_title_hi', 'value' => 'डियरली डाकघर के बारे में', 'type' => 'string'],
            ['key' => 'about_title_ar', 'value' => 'حول مكتب بريد ديرلي', 'type' => 'string'],
            ['key' => 'about_title_es', 'value' => 'Acerca de la Oficina Postal Dearly', 'type' => 'string'],
            ['key' => 'about_title_fr', 'value' => 'À Propos du Bureau de Poste Dearly', 'type' => 'string'],
            ['key' => 'about_title_pt', 'value' => 'Sobre os Correios Dearly', 'type' => 'string'],

            ['key' => 'about_badge_en', 'value' => 'Our Origin & Ethos', 'type' => 'string'],
            ['key' => 'about_badge_bn', 'value' => 'আমাদের সূচনা ও আদর্শ', 'type' => 'string'],
            ['key' => 'about_badge_hi', 'value' => 'हमारी उत्पत्ति और लोकाचार', 'type' => 'string'],
            ['key' => 'about_badge_ar', 'value' => 'أصولنا ورسالتنا', 'type' => 'string'],
            ['key' => 'about_badge_es', 'value' => 'Nuestro Origen y Filosofía', 'type' => 'string'],
            ['key' => 'about_badge_fr', 'value' => 'Notre Origine et Philosophie', 'type' => 'string'],
            ['key' => 'about_badge_pt', 'value' => 'Nossa Origem e Filosofia', 'type' => 'string'],

            ['key' => 'about_heading_en', 'value' => 'The Lost Art of the Slow Letter', 'type' => 'string'],
            ['key' => 'about_heading_bn', 'value' => 'ধীরগতির চিঠির হারিয়ে যাওয়া শিল্প', 'type' => 'string'],
            ['key' => 'about_heading_hi', 'value' => 'धीमे पत्र की खोई हुई कला', 'type' => 'string'],
            ['key' => 'about_heading_ar', 'value' => 'فن الرسائل الهادئة المفقود', 'type' => 'string'],
            ['key' => 'about_heading_es', 'value' => 'El Arte Perdido de la Carta Lenta', 'type' => 'string'],
            ['key' => 'about_heading_fr', 'value' => 'L\'Art Perdu de la Lettre Lente', 'type' => 'string'],
            ['key' => 'about_heading_pt', 'value' => 'A Arte Perdida da Carta Lenta', 'type' => 'string'],

            ['key' => 'about_content_en', 'value' => "In the late decades of the twentieth century, correspondence had a rhythm dictated by distance and paper. One sat at a desk with a warm drink, dipped a pen in ink or rolled a fresh sheet into a typewriter, and poured hours of quiet thought into pages destined for someone across mountains or oceans.\n\nThe days between dispatch and reply were not empty silence—they were filled with joyful anticipation. You knew that somewhere out in the world, your words were traveling in a mailbag, being carried onto trains or ferries, and waiting to be placed into a mailbox.\n\nModern communication has given us instantaneous reach at the expense of intimacy. Notifications arrive like alarms, typing dots demand immediate replies, and messages have dwindled into brief, distracted bursts.\n\nDearly was created as a peaceful antidote. We have preserved the emotional rituals of traditional pen friendships—personalized stationery, collectible postage stamps, digital wax seals, and patient delivery cadences—while harnessing modern cryptography and server-side compatibility matching to connect kindred spirits safely across the globe.", 'type' => 'string'],
            ['key' => 'about_content_bn', 'value' => "বিংশ শতাব্দীর শেষভাগে, চিঠিপত্র বিনিময়ের নিজস্ব এক নিবিড় ছন্দ ছিল—যা নিয়ন্ত্রিত হতো দূরত্বের গভীরতা ও কাগজের স্পর্শে। এক কাপ গরম পানীয় নিয়ে মানুষ লেখার টেবিলে বসত, কালিতে কলম ডুবিয়ে কিংবা টাইপরাইটারে নতুন কাগজ জড়িয়ে ঘণ্টার পর ঘণ্টা নিভৃতে নিজের গভীর চিন্তাভাবনা ঢেলে দিত পাহাড় বা সমুদ্রপারের কোনো প্রিয় মানুষের জন্য।\n\nচিঠি পাঠানো এবং উত্তর পাওয়ার মধ্যবর্তী দিনগুলো কোনো শূন্য নীরবতা ছিল না—বরং তা ছিল এক মধুর ও আনন্দময় প্রতীক্ষা। মনের ভেতর এই বিশ্বাস থাকত যে পৃথিবীর কোথাও না কোথাও ডাকথলিতে করে, রেলগাড়ি বা জাহাজে চড়ে আপনার হৃদয়ের কথাগুলো ভেসে চলেছে কোনো ডাকবাক্সে জমা হওয়ার জন্য।\n\nআধুনিক তথ্যপ্রযুক্তি আমাদের তাৎক্ষণিক যোগাযোগের সুযোগ করে দিয়েছে সত্য, কিন্তু কেড়ে নিয়েছে আন্তরিকতার গভীর অনুভূতি। অ্যালার্মের মতো নোটিফিকেশন বাজে, টাইপিং ডট দ্রুত উত্তরের চাপ সৃষ্টি করে এবং আন্তরিক পত্রালাপ সংকুচিত হয়ে ক্ষণিক ও বিভ্রান্তিকর সংক্ষিপ্ত টেক্সটে পরিণত হয়েছে।\n\nডিয়ারলি তৈরি হয়েছে এর এক শান্ত ও প্রশান্ত বিকল্প হিসেবে। আমরা সনাতন কলম-বন্ধুত্বের আবেগপূর্ণ অনুভূতিগুলোকে সযতনে ফিরিয়ে এনেছি—হ্যান্ডক্রাফটেড স্টেশনারি কাগজ, সংগ্রহযোগ্য ভিন্টেজ ডাকটিকিট, মোমের ডিজিটাল সিলমোহর এবং ধীরগতির স্বাভাবিক ডেলিভারি—পাশাপাশি শক্তিশালী ক্রিপ্টোগ্রাফি ও অ্যালগরিদমের মাধ্যমে বিশ্বজুড়ে সমমনা বন্ধুদের সুরক্ষিতভাবে খুঁজে নেওয়ার মেলবন্ধন ঘটিয়েছি।", 'type' => 'string'],
            ['key' => 'about_content_hi', 'value' => "बीसवीं सदी के अंतिम दशकों में, पत्राचार की एक ऐसी गति थी जो दूरी और कागज़ द्वारा तय होती थी। कोई गर्म पेय लेकर डेस्क पर बैठता था, कलम को स्याही में डुबोता था या टाइपराइटर में एक नया पन्ना लगाता था, और पहाड़ों या महासागरों के पार किसी के लिए घंटों शांत विचारों को पन्नों पर उकेरता था।\n\nपत्र भेजने और उत्तर मिलने के बीच के दिन कोई सूना सन्नाटा नहीं थे—वे सुखद प्रतीक्षा से भरे होते थे। आप जानते थे कि दुनिया में कहीं न कहीं, आपके शब्द डाक के थैले में यात्रा कर रहे हैं, ट्रेनों या नावों में ले जाए जा रहे हैं, और किसी डाकपेटी में रखे जाने का इंतज़ार कर रहे हैं।\n\nआधुनिक संचार ने हमें आत्मीयता की कीमत पर तुरंत पहुँच तो दे दी है, लेकिन गहराई छीन ली है। सूचनाएं अलार्म की तरह बजती हैं, टाइपिंग डॉट्स तुरंत उत्तर की मांग करते हैं, और संदेश संक्षिप्त और विचलित कर देने वाले बन गए हैं।\n\nडियरली को एक शांत और आत्मीय विकल्प के रूप में बनाया गया है। हमने पारंपरिक कलम-दोस्ती के भावनात्मक संस्कारों को सहेजा है—हस्तनिर्मित स्टेशनरी, विंटेज डाक टिकट, डिजिटल मोम की मुहरें और धैर्यपूर्ण डिलीवरी—साथ ही आधुनिक तकनीक के जरिए दुनिया भर में समान विचारधारा वाले मित्रों को सुरक्षित रूप से जोड़ते हैं।", 'type' => 'string'],
            ['key' => 'about_content_ar', 'value' => "في العقود الأخيرة من القرن العشرين، كان للمراسلات إيقاع تفرضه المسافات والورق. كان المرء يجلس إلى مكتبه مع مشروب دافئ، يغمس قلمه في الحبر أو يضع ورقة جديدة في الآلة الكاتبة، ويسكب ساعات من التفكير الهادئ في صفحات مخصصة لشخص ما خلف الجبال أو المحيطات.\n\nلم تكن الأيام الفاصلة بين الإرسال والرد صمتاً فارغاً، بل كانت مفعمة بالترقب والبهجة. كنت تعلم أن كلماتك تسافر في مكان ما من هذا العالم داخل حقيبة بريد، وتُنقل عبر القطارات أو العبارات، بانتظار أن توضع في صندوق البريد.\n\nلقد منحتنا وسائل الاتصال الحديثة سرعة فورية على حساب الحميمية والعمق. تصل الإشعارات كأجراس الإنذار، وتطالب نقاط الكتابة بردود فورية، وانحسرت الرسائل إلى كلمات مقتضبة ومشتتة.\n\nتأسست منصة ديرلي لتكون ملاذاً هادئاً. لقد حافظنا على الطقوس العاطفية لصداقات المراسلة التقليدية—الأوراق المخصصة، وطوابع البريد التذكارية، والأختام الشمعية الرقمية—مع الاستفادة من التشفير الحديث والذكاء التقني لربط الأرواح المتقاربة بأمان حول العالم.", 'type' => 'string'],
            ['key' => 'about_content_es', 'value' => "En las últimas décadas del siglo XX, la correspondencia tenía un ritmo dictado por la distancia y el papel. Uno se sentaba en un escritorio con una bebida caliente, mojaba la pluma en tinta o colocaba una hoja fresca en la máquina de escribir, y volcaba horas de pensamiento sereno en páginas destinadas a alguien al otro lado de montañas u océanos.\n\nLos días entre el envío y la respuesta no eran un silencio vacío: estaban llenos de una gozosa anticipación. Sabías que en algún lugar del mundo, tus palabras viajaban en una saca de correo, en trenes o barcos, esperando ser depositadas en un buzón.\n\nLa comunicación moderna nos ha brindado inmediatez a expensas de la intimidad. Las notificaciones llegan como alarmas, los puntos de escritura exigen respuestas instantáneas y los mensajes se han reducido a ráfagas breves y distraídas.\n\nDearly nació como un antídoto pacífico. Hemos preservado los rituales emocionales de las amistades por correspondencia tradicionales—papelería personalizada, sellos postales de colección, lacres digitales de cera y tiempos de entrega pacientes—utilizando al mismo tiempo criptografía moderna para conectar almas afines de forma segura en todo el mundo.", 'type' => 'string'],
            ['key' => 'about_content_fr', 'value' => "Dans les dernières décennies du vingtième siècle, la correspondance avait un rythme dicté par la distance et le papier. On s'asseyait à un bureau avec une boisson chaude, trempant une plume dans l'encre ou glissant une feuille neuve dans une machine à écrire, consacrant des heures de réflexion paisible à des pages destinées à quelqu'un au-delà des montagnes ou des océans.\n\nLes jours entre l'envoi et la réponse n'étaient pas un silence vide : ils étaient empreints d'une douce anticipation. Vous saviez que quelque part dans le monde, vos mots voyageaient dans un sac postal, transportés par des trains ou des navires, attendant d'être déposés dans une boîte aux lettres.\n\nLa communication moderne nous a apporté l'instantanéité au détriment de l'intimité. Les notifications sonnent comme des alarmes, les indicateurs de frappe réclament des réponses immédiates, et les échanges se sont réduits à de brefs éclats distraits.\n\nDearly a été créé comme un antidote paisible. Nous avons préservé les rituels émotionnels des correspondances traditionnelles—papeterie personnalisée, timbres de collection, sceaux de cire numériques et délais de livraison patients—tout en employant la cryptographie moderne pour unir des esprits complices en toute sécurité.", 'type' => 'string'],
            ['key' => 'about_content_pt', 'value' => "Nas últimas décadas do século XX, a correspondência tinha um ritmo ditado pela distância e pelo papel. Sentava-se à mesa com uma bebida quente, molhava-se a pena na tinta ou colocava-se uma folha nova na máquina de escrever, dedicando horas de pensamentos serenos a páginas destinadas a alguém além de montanhas ou oceanos.\n\nOs dias entre o envio e a resposta não eram um silêncio vazio: eram repletos de uma doce expectativa. Você sabia que em algum lugar do mundo, suas palavras viajavam em uma mala postal, transportadas por trens ou navios, aguardando para serem depositadas em uma caixa de correio.\n\nA comunicação moderna nos deu imediatismo à custa da intimidade. Notificações soam como alarmes, pontos de digitação exigem respostas instantâneas e as mensagens se reduziram a breves lapsos distraídos.\n\nDearly foi criado como um antídoto pacífico. Preservamos os rituais emocionais das tradicionais amizades por correspondência—papel de carta personalizado, selos colecionáveis, lacres de cera digitais e entregas pacientes—utilizando criptografia moderna para conectar espíritos afins com segurança em todo o mundo.", 'type' => 'string'],

            ['key' => 'about_postmaster_title_en', 'value' => 'The Postmaster', 'type' => 'string'],
            ['key' => 'about_postmaster_title_bn', 'value' => 'পোস্টমাস্টার', 'type' => 'string'],
            ['key' => 'about_postmaster_title_hi', 'value' => 'डाकपाल', 'type' => 'string'],
            ['key' => 'about_postmaster_title_ar', 'value' => 'مدير البريد', 'type' => 'string'],
            ['key' => 'about_postmaster_title_es', 'value' => 'El Administrador Postal', 'type' => 'string'],
            ['key' => 'about_postmaster_title_fr', 'value' => 'Le Maître de Poste', 'type' => 'string'],
            ['key' => 'about_postmaster_title_pt', 'value' => 'O Chefe do Correio', 'type' => 'string'],

            ['key' => 'about_postmaster_sub_en', 'value' => 'The Dearly Sanctuary', 'type' => 'string'],
            ['key' => 'about_postmaster_sub_bn', 'value' => 'ডিয়ারলি চিঠির প্রাঙ্গণ', 'type' => 'string'],
            ['key' => 'about_postmaster_sub_hi', 'value' => 'डियरली आश्रय', 'type' => 'string'],
            ['key' => 'about_postmaster_sub_ar', 'value' => 'ملاذ ديرلي', 'type' => 'string'],
            ['key' => 'about_postmaster_sub_es', 'value' => 'El Santuario Dearly', 'type' => 'string'],
            ['key' => 'about_postmaster_sub_fr', 'value' => 'Le Sanctuaire Dearly', 'type' => 'string'],
            ['key' => 'about_postmaster_sub_pt', 'value' => 'O Santuário Dearly', 'type' => 'string'],

            ['key' => 'about_postal_mark_en', 'value' => 'Archival Record', 'type' => 'string'],
            ['key' => 'about_postal_mark_bn', 'value' => 'সংরক্ষিত মহাফেজখানা', 'type' => 'string'],
            ['key' => 'about_postal_mark_hi', 'value' => 'अभिलेखागार रिकॉर्ड', 'type' => 'string'],
            ['key' => 'about_postal_mark_ar', 'value' => 'سجل أرشيفي', 'type' => 'string'],
            ['key' => 'about_postal_mark_es', 'value' => 'Registro de Archivo', 'type' => 'string'],
            ['key' => 'about_postal_mark_fr', 'value' => 'Registre d\'Archives', 'type' => 'string'],
            ['key' => 'about_postal_mark_pt', 'value' => 'Registro de Arquivo', 'type' => 'string'],

            // ==========================================
            // HOW IT WORKS CONTENT (MULTILINGUAL)
            // ==========================================
            ['key' => 'how_it_works_badge_en', 'value' => 'The Postal Manual', 'type' => 'string'],
            ['key' => 'how_it_works_badge_bn', 'value' => 'ডাক নির্দেশিকা', 'type' => 'string'],
            ['key' => 'how_it_works_badge_hi', 'value' => 'डाक नियमावली', 'type' => 'string'],
            ['key' => 'how_it_works_badge_ar', 'value' => 'دليل البريد', 'type' => 'string'],
            ['key' => 'how_it_works_badge_es', 'value' => 'El Manual Postal', 'type' => 'string'],
            ['key' => 'how_it_works_badge_fr', 'value' => 'Le Guide Postal', 'type' => 'string'],
            ['key' => 'how_it_works_badge_pt', 'value' => 'O Manual Postal', 'type' => 'string'],

            ['key' => 'how_it_works_heading_en', 'value' => 'How Correspondence Finds Its Way', 'type' => 'string'],
            ['key' => 'how_it_works_heading_bn', 'value' => 'চিঠিপত্র যেভাবে খুঁজে নেয় তার ঠিকানা', 'type' => 'string'],
            ['key' => 'how_it_works_heading_hi', 'value' => 'पत्राचार कैसे अपनी राह ढूंढता है', 'type' => 'string'],
            ['key' => 'how_it_works_heading_ar', 'value' => 'كيف تجد الرسائل طريقها', 'type' => 'string'],
            ['key' => 'how_it_works_heading_es', 'value' => 'Cómo la Correspondencia Encuentra su Camino', 'type' => 'string'],
            ['key' => 'how_it_works_heading_fr', 'value' => 'Comment la Correspondance Trouve son Chemin', 'type' => 'string'],
            ['key' => 'how_it_works_heading_pt', 'value' => 'Como a Correspondência Encontra seu Caminho', 'type' => 'string'],

            ['key' => 'how_it_works_cta_text_en', 'value' => 'Start Your First Letter', 'type' => 'string'],
            ['key' => 'how_it_works_cta_text_bn', 'value' => 'আপনার প্রথম চিঠি লিখুন', 'type' => 'string'],
            ['key' => 'how_it_works_cta_text_hi', 'value' => 'अपना पहला पत्र शुरू करें', 'type' => 'string'],
            ['key' => 'how_it_works_cta_text_ar', 'value' => 'ابدأ رسالتك الأولى', 'type' => 'string'],
            ['key' => 'how_it_works_cta_text_es', 'value' => 'Comienza Tu Primera Carta', 'type' => 'string'],
            ['key' => 'how_it_works_cta_text_fr', 'value' => 'Commencez Votre Première Lettre', 'type' => 'string'],
            ['key' => 'how_it_works_cta_text_pt', 'value' => 'Comece Sua Primeira Carta', 'type' => 'string'],

            // 5 Steps in English
            [
                'key' => 'how_it_works_steps_en',
                'value' => json_encode([
                    [
                        'step' => 1,
                        'title' => 'Establish Your Postal Identity',
                        'desc' => 'Sign in securely with Google OAuth or email. Choose your unique public pseudonym (e.g. VintageDreamer). You will automatically receive a private mailing code (e.g. DEARLY-7K9M-2Q8P). Your personal email address and internal account credentials remain strictly hidden.'
                    ],
                    [
                        'step' => 2,
                        'title' => 'Take the Discovery Questionnaire',
                        'desc' => 'Select your preferred questionnaire length: 20, 30, 40, or 50 single-answer questions covering 17 deep dimensions including introversion, emotional openness, reading habits, humor, and daily routines. Your answers are saved automatically as you progress.'
                    ],
                    [
                        'step' => 3,
                        'title' => 'Kindred Spirit Matching (90%+ Threshold)',
                        'desc' => 'Our server-side weighted algorithm calculates dimensional compatibility against all completed members. Kindred spirits meeting the 90%+ compatibility threshold appear in your match discovery desk, highlighting shared passions and complementary traits.'
                    ],
                    [
                        'step' => 4,
                        'title' => 'The Letter Studio: Compose, Fold & Seal',
                        'desc' => 'Write in an uncluttered, atmospheric editor with draft autosaving. When finished, enter the folding sequence: choose your envelope, affix a collectible vintage postage stamp, and stamp your personal wax seal. Enter your recipient’s mailing code and confirm dispatch.'
                    ],
                    [
                        'step' => 5,
                        'title' => 'Mailbox Arrival & Authentic Read Receipts',
                        'desc' => 'The letter is safely placed into the recipient\'s post office box. Unopened envelopes await a quiet moment. Only when the recipient legitimately breaks the digital seal and renders the letter content does an authentic read receipt notify the sender: "Your letter has been read."'
                    ],
                ]),
                'type' => 'json'
            ],
            // 5 Steps in Bengali
            [
                'key' => 'how_it_works_steps_bn',
                'value' => json_encode([
                    [
                        'step' => 1,
                        'title' => 'আপনার ডাক পরিচয় তৈরি করুন',
                        'desc' => 'গুগল অথবা ইমেইল দিয়ে নিরাপদে সাইন ইন করুন। আপনার পছন্দের পাবলিক ডাকনাম নির্বাচন করুন (যেমন VintageDreamer)। আপনি স্বয়ংক্রিয়ভাবে একটি ব্যক্তিগত ডাক কোড (যেমন DEARLY-7K9M-2Q8P) পাবেন। আপনার ব্যক্তিগত ইমেইল এবং মূল অ্যাকাউন্ট তথ্য সম্পূর্ণ গোপন থাকবে।'
                    ],
                    [
                        'step' => 2,
                        'title' => 'আবিষ্কারের প্রশ্নাবলীতে অংশ নিন',
                        'desc' => 'আপনার সুবিধামতো ২০, ৩০, ৪০ কিংবা ৫০টি প্রশ্নের পরিধি বেছে নিন—যা অন্তর্মুখী স্বভাব, আবেগ, পড়ার অভ্যাস ও জীবনবোধের ১৭টি গভীর দিক পর্যালোচনা করে। উত্তর দেওয়ার সাথে সাথে তা স্বয়ংক্রিয়ভাবে সংরক্ষিত হয়।'
                    ],
                    [
                        'step' => 3,
                        'title' => 'সমমনা বন্ধুদের মেলবন্ধন (৯০%+ মিল)',
                        'desc' => 'আমাদের সার্ভার-সাইড ওয়েটেড অ্যালগরিদম গভীর সামঞ্জস্যতা হিসেব করে। ৯০% বা তার বেশি মিল পাওয়া সমমনা বন্ধুদের আপনার ডিসকভারি ডেস্কে তুলে ধরা হয়, যেখানে অভিন্ন পছন্দ ও পরিপূরক গুণাবলী দৃশ্যমান থাকে।'
                    ],
                    [
                        'step' => 4,
                        'title' => 'চিঠি লেখার স্টুডিও: লিখুন, ভাঁজ করুন ও সিলমোহর লাগান',
                        'desc' => 'কোলাহলমুক্ত শান্ত পরিবেশে চিঠি লিখুন যাতে ড্রাফট স্বয়ংক্রিয়ভাবে সেভ হয়। লেখা শেষে খাম পছন্দ করুন, সংগ্রাহকের ভিন্টেজ স্ট্যাম্প লাগান এবং ডিজিটাল মোমের সিলমোহর দিন। প্রাপকের ডাক কোড বসিয়ে পাঠান।'
                    ],
                    [
                        'step' => 5,
                        'title' => 'ডাকবাক্সে পৌঁছানো ও খাঁটি পাঠ রশিদ',
                        'desc' => 'চিঠিটি নিরাপদে প্রাপকের পোস্ট বক্সে জমা হয়। প্রাপক যখন সিল ভেঙে চিঠিটি বাস্তবে খুলবেন এবং পাঠ করবেন, ঠিক তখনই প্রেরক নিশ্চিত বার্তা পাবেন: "আপনার চিঠিটি পঠিত হয়েছে।"'
                    ],
                ]),
                'type' => 'json'
            ],
            // 5 Steps in Hindi
            [
                'key' => 'how_it_works_steps_hi',
                'value' => json_encode([
                    [
                        'step' => 1,
                        'title' => 'अपनी डाक पहचान बनाएं',
                        'desc' => 'गूगल या ईमेल से सुरक्षित लॉगिन करें। अपना अनूठा सार्वजनिक उपनाम चुनें। आपको स्वतः एक निजी डाक कोड (जैसे DEARLY-7K9M-2Q8P) मिलेगा। आपका व्यक्तिगत ईमेल पता पूरी तरह गुप्त रहेगा।'
                    ],
                    [
                        'step' => 2,
                        'title' => 'खोज प्रश्नावली में भाग लें',
                        'desc' => 'अपनी पसंद के अनुसार 20, 30, 40 या 50 प्रश्नों की लंबाई चुनें, जो व्यक्तित्व और रुचियों के 17 आयामों को छूते हैं।'
                    ],
                    [
                        'step' => 3,
                        'title' => 'समान विचारधारा वाले मित्रों का मिलान (90%+ मिलान)',
                        'desc' => 'हमारा एल्गोरिदम गहरी अनुकूलता की गणना करता है और 90% से अधिक संगतता वाले आत्मीय मित्रों को आपके सामने प्रस्तुत करता है।'
                    ],
                    [
                        'step' => 4,
                        'title' => 'पत्र स्टूडियो: लिखें, मोड़ें और मुहर लगाएं',
                        'desc' => 'शांत वातावरण में पत्र लिखें। विंटेज डाक टिकट चुनें, अपनी मोम की मुहर लगाएं और प्राप्तकर्ता के डाक कोड पर भेजें।'
                    ],
                    [
                        'step' => 5,
                        'title' => 'डाकपेटी में आगमन और प्रामाणिक पावती',
                        'desc' => 'पत्र प्राप्तकर्ता के डाक बक्से में सुरक्षित पहुंचता है। जब वे मोहर तोड़कर पत्र पढ़ते हैं, तभी प्रेषक को प्रामाणिक रसीद मिलती है।'
                    ],
                ]),
                'type' => 'json'
            ],
            // 5 Steps in Arabic
            [
                'key' => 'how_it_works_steps_ar',
                'value' => json_encode([
                    [
                        'step' => 1,
                        'title' => 'أنشئ هويتك البريدية',
                        'desc' => 'سجّل الدخول بأمان عبر جوجل أو البريد الإلكتروني. اختر اسمك المستعار. ستحصل تلقائياً على رمز بريدي خاص (مثل DEARLY-7K9M-2Q8P) مع الحفاظ على سرية بريدك الإلكتروني تماماً.'
                    ],
                    [
                        'step' => 2,
                        'title' => 'أجب عن استبيان الاكتشاف',
                        'desc' => 'اختر عدد الأسئلة المفضل: 20 أو 30 أو 40 أو 50 سؤالاً تغطي 17 بُعداً نفسياً وعاطفياً وأسلوب الحياة.'
                    ],
                    [
                        'step' => 3,
                        'title' => 'التوافق مع الأرواح المتقاربة (90%+ فما فوق)',
                        'desc' => 'تحسب خوارزميتنا درجات التوافق الدقيقة لترشيح الأصدقاء الذين يحققون نسبة 90% فما فوق من الاهتمامات المشتركة.'
                    ],
                    [
                        'step' => 4,
                        'title' => 'استوديو الرسائل: اكتب واطوِ واختم بالشمع',
                        'desc' => 'اكتب في محرر نقي، واختر ظرفك وطوابعك النادرة وضع ختم الشمع الرقمي الخاص بك ثم أكد الإرسال.'
                    ],
                    [
                        'step' => 5,
                        'title' => 'الوصول إلى الصندوق وإشعار القراءة الحقيقي',
                        'desc' => 'تصل الرسالة إلى صندوق بريد المستلم. وفقط عندما يكسر الختم ويقرأ الرسالة فعلياً، يصلك إشعار صادق: "تمت قراءة رسالتك".'
                    ],
                ]),
                'type' => 'json'
            ],
            // 5 Steps in Spanish
            [
                'key' => 'how_it_works_steps_es',
                'value' => json_encode([
                    [
                        'step' => 1,
                        'title' => 'Establece tu Identidad Postal',
                        'desc' => 'Inicia sesión de forma segura con Google o correo electrónico. Elige tu seudónimo público. Recibirás automáticamente un código postal privado (ej. DEARLY-7K9M-2Q8P). Tu correo electrónico permanece estrictamente oculto.'
                    ],
                    [
                        'step' => 2,
                        'title' => 'Responde el Cuestionario de Descubrimiento',
                        'desc' => 'Selecciona 20, 30, 40 o 50 preguntas que exploran 17 dimensiones profundas de personalidad y estilo de vida.'
                    ],
                    [
                        'step' => 3,
                        'title' => 'Emparejamiento de Almas Afines (Umbral 90%+)',
                        'desc' => 'Nuestro algoritmo calcula la compatibilidad y presenta correspondencias con un 90% o más de afinidad.'
                    ],
                    [
                        'step' => 4,
                        'title' => 'El Estudio de Cartas: Escribe, Dobla y Sella',
                        'desc' => 'Escribe en un editor sereno, elige tu sobre, añade un sello vintage y estampa tu sello de cera digital.'
                    ],
                    [
                        'step' => 5,
                        'title' => 'Llegada al Buzón y Confirmación Auténtica',
                        'desc' => 'La carta llega al buzón del destinatario. Solo cuando rompe el sello y lee el contenido, se notifica al remitente que ha sido leída.'
                    ],
                ]),
                'type' => 'json'
            ],
            // 5 Steps in French
            [
                'key' => 'how_it_works_steps_fr',
                'value' => json_encode([
                    [
                        'step' => 1,
                        'title' => 'Établissez Votre Identité Postale',
                        'desc' => 'Connectez-vous en toute sécurité avec Google ou par e-mail. Choisissez votre pseudonyme. Vous recevrez un code postal privé (ex. DEARLY-7K9M-2Q8P) tandis que votre adresse e-mail reste totalement confidentielle.'
                    ],
                    [
                        'step' => 2,
                        'title' => 'Répondez au Questionnaire de Découverte',
                        'desc' => 'Choisissez 20, 30, 40 ou 50 questions explorant 17 dimensions profondes de personnalité et d\'habitudes de vie.'
                    ],
                    [
                        'step' => 3,
                        'title' => 'Affinités Électives (Seuil 90%+)',
                        'desc' => 'Notre algorithme calcule la compatibilité et présente les correspondants atteignant 90% ou plus de résonance.'
                    ],
                    [
                        'step' => 4,
                        'title' => 'L\'Atelier Postal : Rédigez, Pliez et Scellez',
                        'desc' => 'Rédigez dans un éditeur apaisant, choisissez votre enveloppe, collez un timbre vintage et apposez votre sceau de cire.'
                    ],
                    [
                        'step' => 5,
                        'title' => 'Arrivée en Boîte et Accusé de Lecture Authentique',
                        'desc' => 'La lettre repose dans la boîte postale du destinataire. Ce n\'est que lorsqu\'il brise le sceau et lit la lettre qu\'un accusé authentique vous parvient.'
                    ],
                ]),
                'type' => 'json'
            ],
            // 5 Steps in Portuguese
            [
                'key' => 'how_it_works_steps_pt',
                'value' => json_encode([
                    [
                        'step' => 1,
                        'title' => 'Estabeleça Sua Identidade Postal',
                        'desc' => 'Entre com segurança pelo Google ou e-mail. Escolha seu pseudônimo. Você receberá automaticamente um código postal privado (ex. DEARLY-7K9M-2Q8P) com seu e-mail pessoal mantido em absoluto sigilo.'
                    ],
                    [
                        'step' => 2,
                        'title' => 'Faça o Questionário de Descoberta',
                        'desc' => 'Escolha entre 20, 30, 40 ou 50 perguntas que exploram 17 dimensões profundas de personalidade e estilo de vida.'
                    ],
                    [
                        'step' => 3,
                        'title' => 'Almas Afins (Limiar 90%+)',
                        'desc' => 'Nosso algoritmo calcula a afinidade e apresenta conexões com 90% ou mais de compatibilidade.'
                    ],
                    [
                        'step' => 4,
                        'title' => 'O Estúdio de Cartas: Escreva, Dobre e Sele',
                        'desc' => 'Escreva em um editor tranquilo, escolha o envelope, adicione um selo vintage e carimbe seu lacre de cera digital.'
                    ],
                    [
                        'step' => 5,
                        'title' => 'Chegada à Caixa Postal e Confirmação Autêntica',
                        'desc' => 'A carta aguarda na caixa postal. Somente quando o destinatário quebra o lacre e lê a carta, você recebe a notificação de leitura autêntica.'
                    ],
                ]),
                'type' => 'json'
            ],

            // ==========================================
            // FAQS (MULTILINGUAL)
            // ==========================================
            [
                'key' => 'faqs_en',
                'value' => json_encode([
                    [
                        'q' => 'How is Dearly different from an instant messaging app or email?',
                        'a' => 'Dearly is intentionally slow. Letters take time to travel and be received. There are no online presence dots, typing indicators, or pressure to respond instantly. Each letter is crafted on beautiful digital stationery, packaged inside an envelope with collectible stamps, and sealed with virtual wax.'
                    ],
                    [
                        'q' => 'Is my personal email address or real name visible to other users?',
                        'a' => 'Never. Dearly enforces strict privacy by design. Other members only see your chosen public nickname, broad age range, and shared interests. Letters are sent using unique private mailing codes (e.g., DEARLY-7K9M-2Q8P).'
                    ],
                    [
                        'q' => 'How does the friendship compatibility algorithm work?',
                        'a' => 'You choose a 20, 30, 40, or 50-question discovery questionnaire covering 17 personality, lifestyle, and communication dimensions. Our server-side weighted scoring algorithm compares your answers to find kindred spirits with a 90% or higher compatibility score.'
                    ],
                    [
                        'q' => 'What is the daily sending limit?',
                        'a' => 'To preserve the thoughtful and intimate quality of correspondence and prevent spam, users can dispatch up to 5 letters per day, with a maximum of 2 new pen-pal introductions per day.'
                    ],
                    [
                        'q' => 'Are read receipts real or automatic?',
                        'a' => 'Read receipts are authentic: they are triggered only when the authorized recipient legitimately opens and renders your letter inside their Dearly post office.'
                    ],
                ]),
                'type' => 'json'
            ],
            [
                'key' => 'faqs_bn',
                'value' => json_encode([
                    [
                        'q' => 'ডিয়ারলি কীভাবে সাধারণ ইনস্ট্যান্ট মেসেজিং বা ইমেইল থেকে আলাদা?',
                        'a' => 'ডিয়ারলি একটি ধীরগতির প্রাঙ্গণ। এখানে চিঠি পৌঁছাতে সময় লাগে। কোনো অনলাইন অ্যাক্টিভ ডট, টাইপিং ইন্ডিকেটর বা সাথে সাথে উত্তর দেওয়ার চাপ নেই। প্রতিটি চিঠি সুন্দর ভিন্টেজ স্টেশনারি কাগজে লেখা হয়, খামে ভরা হয় এবং মোমের সিলমোহর দিয়ে পাঠানো হয়।'
                    ],
                    [
                        'q' => 'আমার ব্যক্তিগত ইমেইল বা আসল নাম কি অন্য ব্যবহারকারীরা দেখতে পাবে?',
                        'a' => 'কখনোই না। ডিয়ারলি কঠোর গোপনীয়তা বজায় রাখে। অন্য সদস্যরা কেবল আপনার নির্বাচিত ছদ্মনাম ও আগ্রহ দেখতে পায়। চিঠি পাঠানো হয় গোপন ডাক কোডের মাধ্যমে (যেমন DEARLY-7K9M-2Q8P)।'
                    ],
                    [
                        'q' => 'বন্ধুত্ব ও ব্যক্তিত্বের মেলবন্ধন অ্যালগরিদম কীভাবে কাজ করে?',
                        'a' => 'আপনি ২০, ৩০, ৪০ বা ৫০টি প্রশ্নের মনস্তাত্ত্বিক প্রশ্নাবলীতে অংশ নেন। আমাদের সার্ভার-সাইড অ্যালগরিদম উত্তরগুলো মিলিয়ে ৯০% বা তার বেশি মিল পাওয়া সমমনা বন্ধুদের খুঁজে বের করে।'
                    ],
                    [
                        'q' => 'দৈনিক চিঠি পাঠানোর সর্বোচ্চ সীমা কত?',
                        'a' => 'চিঠির আন্তরিকতা রক্ষা ও স্প্যাম রোধে প্রতিদিন সর্বোচ্চ ৫টি চিঠি পাঠানো যায় এবং দিনে সর্বোচ্চ ২টি নতুন পরিচিতি শুরু করা যায়।'
                    ],
                    [
                        'q' => 'চিঠি পড়ার রশিদ (Read Receipts) কি স্বয়ংক্রিয় নাকি বাস্তব?',
                        'a' => 'পাঠ রশিদ সম্পূর্ণ বাস্তব। প্রাপক যখন ডাকঘরে খামের সিল ভেঙে চিঠিটি বাস্তবে পাঠ করেন, ঠিক তখনই প্রেরক নিশ্চিত নোটিফিকেশন পান।'
                    ],
                ]),
                'type' => 'json'
            ],
            [
                'key' => 'faqs_hi',
                'value' => json_encode([
                    [
                        'q' => 'डियरली किसी त्वरित संदेश (इंस्टेंट मैसेजिंग) ऐप या ईमेल से कैसे भिन्न है?',
                        'a' => 'डियरली को जानबूझकर शांत और धीमा बनाया गया है। यहाँ पत्रों को यात्रा करने और पहुँचने में स्वाभाविक समय लगता है। कोई ऑनलाइन प्रेजेंस डॉट्स, टाइपिंग इंडिकेटर या तुरंत उत्तर देने का दबाव नहीं है। प्रत्येक पत्र सुंदर विंटेज स्टेशनरी पर तैयार किया जाता है, लिफाफे में बंद किया जाता है और डिजिटल मोम की मुहर से सील किया जाता है।'
                    ],
                    [
                        'q' => 'क्या मेरा व्यक्तिगत ईमेल पता या असली नाम अन्य उपयोगकर्ताओं को दिखाई देता है?',
                        'a' => 'कभी नहीं। डियरली सख्त गोपनीयता मानकों का पालन करता है। अन्य सदस्यों को केवल आपका चुना हुआ उपनाम, आयु वर्ग और साझा रुचियां ही दिखाई देती हैं। पत्र केवल अद्वितीय निजी डाक कोड (जैसे DEARLY-7K9M-2Q8P) के माध्यम से भेजे जाते हैं।'
                    ],
                    [
                        'q' => 'मित्रता और व्यक्तित्व अनुकूलता एल्गोरिदम कैसे काम करता है?',
                        'a' => 'आप 17 व्यक्तित्व और जीवनशैली आयामों को कवर करने वाली 20, 30, 40 या 50 प्रश्नों की खोज प्रश्नावली चुनते हैं। हमारा सर्वर-साइड वेटेड एल्गोरिदम 90% या उससे अधिक अनुकूलता वाले समान विचारधारा वाले मित्रों को खोजता है।'
                    ],
                    [
                        'q' => 'दैनिक पत्र भेजने की सीमा क्या है?',
                        'a' => 'पत्रों की आत्मीयता बनाए रखने और स्पैम को रोकने के लिए, उपयोगकर्ता प्रति दिन अधिकतम 5 पत्र भेज सकते हैं, जिसमें प्रति दिन अधिकतम 2 नए पेन-पाल परिचय शामिल हैं।'
                    ],
                    [
                        'q' => 'क्या पढ़े जाने की पुष्टि (Read Receipts) वास्तविक है या स्वचालित?',
                        'a' => 'पढ़े जाने की पुष्टि पूरी तरह प्रामाणिक है: यह केवल तभी दर्ज होती है जब अधिकृत प्राप्तकर्ता अपने डियरली डाकघर में आपके पत्र का मोम का सील तोड़कर उसे वास्तव में पढ़ता है।'
                    ],
                ]),
                'type' => 'json'
            ],
            [
                'key' => 'faqs_ar',
                'value' => json_encode([
                    [
                        'q' => 'كيف تختلف منصة ديرلي عن تطبيقات المراسلة الفورية أو البريد الإلكتروني التقليدي؟',
                        'a' => 'ديرلي منصة هادئة ومتمهلة عن قصد. تحتاج الرسائل وقتاً لتسافر وتصل. لا توجد مؤشرات اتصال خضراء، ولا نقاط كتابة، ولا ضغوط للرد الفوري. تُصاغ كل رسالة على ورق قرطاسية عتيق، وتوضع في مظروف يحمل طوابع بريدية قابلة للجمع، وتُختم بشمع رقمي دافئ.'
                    ],
                    [
                        'q' => 'هل يظهر بريدي الإلكتروني الشخصي أو اسمي الحقيقي للمستخدمين الآخرين؟',
                        'a' => 'أبداً. تلتزم ديرلي بأقصى درجات الخصوصية التامة. لا يرى الأعضاء الآخرون سوى اسمك المستعار المختار، وفئتك العمرية التقريبية، واهتماماتك المشتركة. تُرسل الرسائل باستخدام رموز بريدية خاصة وفريدة (مثل DEARLY-7K9M-2Q8P).'
                    ],
                    [
                        'q' => 'كيف تعمل خوارزمية التوافق والتعارف بين الأصدقاء؟',
                        'a' => 'تشارك في استبيان استكشافي مكون من 20 أو 30 أو 40 أو 50 سؤالاً يغطي 17 بعداً شخصياً وفكرياً. تقارن خوارزميتنا الموزونة إجاباتك للعثور على أرواح متقاربة بنسبة توافق 90% أو أكثر.'
                    ],
                    [
                        'q' => 'ما هو الحد اليومي لإرسال الرسائل البريدية؟',
                        'a' => 'للحفاظ على عمق المراسلة وصدقها ومنع الرسائل العشوائية، يمكن إرسال ما يصل إلى 5 رسائل يومياً، بحد أقصى تعارفين جديدين في اليوم الواحد.'
                    ],
                    [
                        'q' => 'هل إشعارات قراءة الرسائل حقيقية أم تلقائية؟',
                        'a' => 'إشعارات القراءة حقيقية وأصيلة تماماً؛ يتم تفعيلها فقط عندما يقوم المستلم المعتمد بفض ختم الشمع وفتح الرسالة فعلياً داخل مكتب بريد ديرلي الخاص به.'
                    ],
                ]),
                'type' => 'json'
            ],
            [
                'key' => 'faqs_es',
                'value' => json_encode([
                    [
                        'q' => '¿En qué se diferencia Dearly de una app de mensajería instantánea o del correo convencional?',
                        'a' => 'Dearly es intencionalmente pausada. Las cartas toman tiempo para viajar y entregarse. No existen indicadores de "en línea", puntos de escritura ni urgencia por responder al instante. Cada carta se redacta en hermosa papelería digital vintage, dentro de un sobre con sellos coleccionables y sellada con lacre virtual.'
                    ],
                    [
                        'q' => '¿Es visible mi correo electrónico personal o mi nombre real para otros usuarios?',
                        'a' => 'Bajo ninguna circunstancia. Dearly aplica privacidad rigurosa por diseño. Los demás miembros únicamente pueden ver tu apodo público, tu rango de edad aproximado y tus intereses compartidos. Las cartas se intercambian a través de códigos postales privados únicos (ej. DEARLY-7K9M-2Q8P).'
                    ],
                    [
                        'q' => '¿Cómo funciona el algoritmo de compatibilidad entre amigos por correspondencia?',
                        'a' => 'Completas un cuestionario de descubrimiento de 20, 30, 40 o 50 preguntas que abarca 17 dimensiones de personalidad y estilo de vida. Nuestro algoritmo compara tus respuestas para conectarte con almas afines con un 90% o más de compatibilidad.'
                    ],
                    [
                        'q' => '¿Cuál es el límite diario de envío de cartas?',
                        'a' => 'Para preservar el valor reflexivo de la correspondencia y evitar el correo no deseado, cada usuario puede despachar hasta 5 cartas al día, con un máximo de 2 nuevas presentaciones por jornada.'
                    ],
                    [
                        'q' => '¿Son auténticas o simuladas las confirmaciones de lectura?',
                        'a' => 'Las confirmaciones de lectura son estrictamente auténticas: se registran solo cuando el destinatario legítimo rompe el sello de cera y despliega la carta en su oficina postal de Dearly.'
                    ],
                ]),
                'type' => 'json'
            ],
            [
                'key' => 'faqs_fr',
                'value' => json_encode([
                    [
                        'q' => 'En quoi Dearly diffère-t-il d\'une application de messagerie instantanée ou d\'un e-mail ?',
                        'a' => 'Dearly est délibérément lent et apaisant. Les lettres prennent du temps pour voyager. Il n\'y a ni indicateur de présence en ligne, ni points de frappe, ni pression pour répondre sur-le-champ. Chaque lettre est composée sur du papier à lettres d\'époque, glissée dans une enveloppe affranchie de timbres de collection et scellée à la cire numérique.'
                    ],
                    [
                        'q' => 'Mon adresse e-mail personnelle ou mon vrai nom sont-ils visibles pour les autres membres ?',
                        'a' => 'Jamais. Dearly applique une politique de confidentialité stricte dès la conception. Les autres membres ne voient que votre pseudonyme choisi, votre tranche d\'âge et vos centres d\'intérêt partagés. Les lettres s\'échangent via des codes postaux privés uniques (ex. DEARLY-7K9M-2Q8P).'
                    ],
                    [
                        'q' => 'Comment fonctionne l\'algorithme d\'affinité épistolaire ?',
                        'a' => 'Vous répondez à un questionnaire de découverte de 20, 30, 40 ou 50 questions couvrant 17 dimensions de personnalité et d\'intérêts. Notre algorithme côté serveur compare vos réponses pour identifier des esprits complices avec un score de compatibilité de 90 % ou plus.'
                    ],
                    [
                        'q' => 'Quelle est la limite quotidienne d\'envoi de lettres ?',
                        'a' => 'Pour préserver l\'authenticité des échanges et prévenir le spam, chaque membre peut envoyer jusqu\'à 5 lettres par jour, avec un maximum de 2 nouvelles prises de contact quotidiennes.'
                    ],
                    [
                        'q' => 'Les accusés de lecture sont-ils réels ou automatiques ?',
                        'a' => 'Les accusés de lecture sont authentiques : ils ne sont déclenchés que lorsque le destinataire légitime brise le cachet de cire et ouvre réellement votre lettre dans son bureau de poste Dearly.'
                    ],
                ]),
                'type' => 'json'
            ],
            [
                'key' => 'faqs_pt',
                'value' => json_encode([
                    [
                        'q' => 'Como o Dearly se diferencia de um aplicativo de mensagens instantâneas ou e-mail comum?',
                        'a' => 'O Dearly é intencionalmente calmo e pausado. As cartas levam tempo para viajar. Não há luzes de status online, pontos de digitação nem pressa para responder. Cada carta é escrita em elegante papel de carta vintage, acomodada em envelope com selos colecionáveis e lacrada com cera digital.'
                    ],
                    [
                        'q' => 'Meu e-mail pessoal ou nome verdadeiro ficam visíveis para outros usuários?',
                        'a' => 'De forma alguma. O Dearly adota privacidade rigorosa por princípio. Os outros membros só visualizam seu pseudônimo escolhido, faixa etária aproximada e interesses em comum. As correspondências ocorrem por meio de códigos postais privados exclusivos (ex.: DEARLY-7K9M-2Q8P).'
                    ],
                    [
                        'q' => 'Como funciona o algoritmo de compatibilidade de correspondentes?',
                        'a' => 'Você responde a um questionário de 20, 30, 40 ou 50 perguntas que explora 17 dimensões de personalidade e estilo de vida. Nosso algoritmo compara suas respostas para encontrar almas afins com 90% ou mais de compatibilidade.'
                    ],
                    [
                        'q' => 'Qual é o limite diário de envio de cartas?',
                        'a' => 'Para preservar o carinho e o cuidado nas palavras e impedir o spam, cada usuário pode despachar até 5 cartas por dia, com no máximo 2 apresentações a novos correspondentes diárias.'
                    ],
                    [
                        'q' => 'As confirmações de leitura são reais ou automáticas?',
                        'a' => 'As confirmações de leitura são autênticas: elas só são registradas quando o destinatário quebra o lacre de cera e realmente abre a carta em sua agência postal Dearly.'
                    ],
                ]),
                'type' => 'json'
            ],

            // Base Policy Defaults — Community Guidelines
            [
                'key' => 'community_guidelines_en',
                'value' => "### The Dearly Post Office Community Guidelines\n\n1. **Kindness & Courtesy**: Treat every correspondent with dignity, empathy, and respect.\n2. **Slow Correspondence**: Understand that life moves in seasons; respect your pen friends' response rhythms without pressure.\n3. **Privacy First**: Do not pressure others to reveal personal contact details, physical addresses, or external social media profiles.\n4. **Zero Tolerance for Abuse**: Harassment, hate speech, spam, unsolicited commercial advertisements, and inappropriate content result in immediate account suspension.",
                'type' => 'string'
            ],
            [
                'key' => 'community_guidelines_bn',
                'value' => "### ডিয়ারলি পোস্ট অফিস কমিউনিটি নির্দেশিকা\n\n১. **সৌহার্দ্য ও সম্মান**: প্রতিটি পত্রবন্ধুর সাথে বিনম্র ও শ্রদ্ধাশীল আচরণ করুন।\n২. **ধীরগতির পত্রালাপ**: জীবনের নিজস্ব গতি রয়েছে; চিঠির উত্তরের জন্য চাপ সৃষ্টি না করে সঙ্গীর সময়কে সম্মান দিন।\n৩. **গোপনীয়তা প্রথম**: ব্যক্তিগত যোগাযোগের মাধ্যম, ঠিকানা বা সোশ্যাল মিডিয়া প্রোফাইল চাওয়ার জন্য কাউকে চাপ দেওয়া যাবে না।\n৪. **অশালীনতার বিরুদ্ধে জিরো টলারেন্স**: যেকোনো হয়রানি, বিদ্বেষপূর্ণ বক্তব্য, স্প্যাম এবং অবাঞ্ছিত বিজ্ঞাপন তাৎক্ষণিক অ্যাকাউন্ট স্থগিতের কারণ হবে।",
                'type' => 'string'
            ],
            [
                'key' => 'community_guidelines_hi',
                'value' => "### डियरली डाकघर समुदाय दिशानिर्देश\n\n१. **सौहार्द और शिष्टाचार**: प्रत्येक पत्र-मित्र के साथ सम्मान, सहानुभूति और गरिमापूर्ण व्यवहार करें।\n२. **धीमा पत्राचार**: समझें कि जीवन की अपनी स्वाभाविक गति होती है; बिना किसी दबाव के अपने मित्रों के जवाब देने की लय का सम्मान करें।\n३. **गोपनीयता सर्वोपरि**: दूसरों पर व्यक्तिगत संपर्क विवरण, भौतिक पता या सोशल मीडिया प्रोफाइल साझा करने का दबाव न बनाएं।\n४. **दुर्व्यवहार के प्रति शून्य सहिष्णुता**: उत्पीड़न, अभद्र भाषा, स्पैम, अवांछित व्यावसायिक विज्ञापन और अनुचित सामग्री पाए जाने पर खाता तुरंत निलंबित कर दिया जाएगा।",
                'type' => 'string'
            ],
            [
                'key' => 'community_guidelines_ar',
                'value' => "### ميثاق مجتمع مكتب بريد ديرلي\n\n١. **اللطف والاحترام**: تعامل مع كل صديق مراسلة بكرامة وتقدير وتعاطف صادق.\n٢. **المراسلة الهادئة**: لكل إنسان وتيرة حياته وظروفه الخاصة؛ احترم أوقات أصدقائك في الرد دون ممارسة أي ضغوط.\n٣. **الخصوصية أولاً**: تجنب تماماً مطالبة الآخرين بوسائل تواصل شخصية، أو عناوين إقامتهم الفعلية، أو حساباتهم في وسائل التواصل الاجتماعي.\n٤. **عدم التسامح مع الإساءة**: المضايقات، وخطاب الكراهية، والرسائل الإعلانية غير المرغوبة، والمحتوى غير اللائق تؤدي فوراً إلى حظر الحساب نهائياً.",
                'type' => 'string'
            ],
            [
                'key' => 'community_guidelines_es',
                'value' => "### Directrices de la Comunidad Postal de Dearly\n\n1. **Amabilidad y Cortesía**: Trata a cada amigo por correspondencia con dignidad, empatía y profundo respeto.\n2. **Correspondencia Pausada**: Comprende que la vida transcurre a su propio compás; respeta el ritmo de respuesta de tus amigos sin presiones.\n3. **Privacidad Ante Todo**: No presiones a otros para que compartan datos de contacto personales, direcciones físicas o perfiles de redes sociales.\n4. **Tolerancia Cero al Abuso**: El acoso, el discurso de odio, el spam, la publicidad comercial no deseada y el contenido inapropiado conllevan la suspensión inmediata de la cuenta.",
                'type' => 'string'
            ],
            [
                'key' => 'community_guidelines_fr',
                'value' => "### Charte de la Communauté du Bureau de Poste Dearly\n\n1. **Bienveillance et Courtoisie** : Traitez chaque correspondant avec dignité, empathie et le plus grand respect.\n2. **Correspondance Paisible** : La vie a ses propres saisons ; respectez le rythme de réponse de vos correspondants sans exercer de pression.\n3. **Priorité à la Confidentialité** : Ne sollicitez pas les coordonnées privées, adresses physiques ou réseaux sociaux de vos correspondants.\n4. **Tolérance Zéro pour les Abus** : Le harcèlement, les propos haineux, le spam, les sollicitations commerciales et les contenus inappropriés entraînent la suspension immédiate du compte.",
                'type' => 'string'
            ],
            [
                'key' => 'community_guidelines_pt',
                'value' => "### Diretrizes da Comunidade Postal Dearly\n\n1. **Gentileza e Respeito**: Trate cada amigo correspondente com dignidade, empatia e cordialidade genuína.\n2. **Correspondência no Seu Tempo**: A vida tem estações próprias; respeite o ritmo de resposta dos seus correspondentes sem cobranças.\n3. **Privacidade em Primeiro Lugar**: Não pressione outros membros a revelar contatos externos, endereços físicos ou redes sociais.\n4. **Tolerância Zero a Abusos**: Assédio, discurso de ódio, spam, anúncios comerciais e conteúdos impróprios resultam em suspensão imediata da conta.",
                'type' => 'string'
            ],

            // Base Policy Defaults — Privacy Policy
            [
                'key' => 'privacy_policy_en',
                'value' => "### Dearly Privacy Policy\n\nDearly is built on the fundamental principle that private correspondence should remain private.\n\n- We never sell, rent, or monetize your personal correspondence or demographic data.\n- We do not reveal your email address, Google account information, or real name to other users.\n- Letters are encrypted in transit and isolated in storage strictly to the sender and intended recipient.\n- You may request data export or complete account deletion at any time through account settings.",
                'type' => 'string'
            ],
            [
                'key' => 'privacy_policy_bn',
                'value' => "### ডিয়ারলি গোপনীয়তা নীতি\n\nডিয়ারলি এই দৃঢ় বিশ্বাসের ওপর প্রতিষ্ঠিত যে ব্যক্তিগত চিঠি সর্বদাই ব্যক্তিগত থাকা উচিত।\n\n- আমরা কখনোই আপনার চিঠিপত্র বা তথ্য কারো কাছে বিক্রি বা বাণিজ্যিকভাবে ব্যবহার করি না।\n- আপনার ব্যক্তিগত ইমেইল বা আসল নাম অন্য সদস্যদের কাছে কখনোই প্রকাশ করা হয় না।\n- প্রতিটি চিঠি এনক্রিপ্ট করা থাকে এবং কেবল প্রেরক ও নির্দিষ্ট প্রাপকের জন্যই সংরক্ষিত থাকে।\n- আপনি যেকোনো সময় সেটিংস থেকে আপনার তথ্য এক্সপোর্ট বা সম্পূর্ণ অ্যাকাউন্ট মুছে ফেলতে পারেন।",
                'type' => 'string'
            ],
            [
                'key' => 'privacy_policy_hi',
                'value' => "### डियरली गोपनीयता नीति\n\nडियरली इस मौलिक सिद्धांत पर स्थापित है कि निजी पत्राचार हमेशा निजी ही रहना चाहिए।\n\n- हम आपके व्यक्तिगत पत्राचार या जनसांख्यिकीय डेटा को कभी भी बेचते, किराए पर देते या व्यावसायिक रूप से उपयोग नहीं करते हैं।\n- हम आपका व्यक्तिगत ईमेल पता, गूगल खाता विवरण या असली नाम अन्य उपयोगकर्ताओं के सामने कभी प्रकट नहीं करते।\n- सभी पत्र पारगमन में एन्क्रिप्टेड होते हैं और केवल प्रेषक तथा अभीष्ट प्राप्तकर्ता के लिए सुरक्षित रहते हैं।\n- आप किसी भी समय अपनी सेटिंग्स से अपना डेटा निर्यात कर सकते हैं या खाता पूरी तरह हटा सकते हैं।",
                'type' => 'string'
            ],
            [
                'key' => 'privacy_policy_ar',
                'value' => "### سياسة خصوصية ديرلي\n\nتأسست ديرلي على مبدأ راسخ هو أن المراسلات الخاصة يجب أن تظل خاصة ومحمية دائماً.\n\n- لا نقوم مطلقاً ببيع، أو تأجير، أو استغلال رسائلك أو بياناتك الشخصية لأي أغراض تجارية.\n- لا نكشف أبداً عن بريدك الإلكتروني الشخصي، أو معلومات حساب جوجل، أو اسمك الحقيقي للمستخدمين الآخرين.\n- جميع الرسائل مشفرة أثناء الإرسال ومحفوظة بسرية تامة تقتصر فقط على المرسل والمستلم المقصود.\n- يمكنك في أي وقت تصدير بياناتك أو حذف حسابك وسجلاتك نهائياً من خلال الإعدادات.",
                'type' => 'string'
            ],
            [
                'key' => 'privacy_policy_es',
                'value' => "### Política de Privacidad de Dearly\n\nDearly se fundamenta en el principio inquebrantable de que la correspondencia privada debe permanecer estrictamente privada.\n\n- Nunca vendemos, alquilamos ni comercializamos tus cartas privadas ni tus datos demográficos.\n- No revelamos tu correo electrónico personal, tu cuenta de Google ni tu nombre real a otros usuarios.\n- Las cartas se cifran en tránsito y se conservan de forma aislada exclusivamente para el remitente y el destinatario legítimo.\n- Puedes solicitar la exportación de tus datos o la eliminación completa de tu cuenta en cualquier momento desde la configuración.",
                'type' => 'string'
            ],
            [
                'key' => 'privacy_policy_fr',
                'value' => "### Politique de Confidentialité de Dearly\n\nDearly repose sur le principe fondamental selon lequel la correspondance intime doit toujours demeurer strictement privée.\n\n- Nous ne vendons, ne louons et ne monétisons jamais vos lettres personnelles ni vos données démographiques.\n- Nous ne divulguons jamais votre adresse e-mail personnelle, votre compte Google ou votre nom d'état civil aux autres membres.\n- Toutes les lettres sont chiffrées en transit et strictement confinées à l'expéditeur et au destinataire prévu.\n- Vous pouvez à tout moment exporter l'ensemble de vos données ou supprimer définitivement votre compte depuis les paramètres.",
                'type' => 'string'
            ],
            [
                'key' => 'privacy_policy_pt',
                'value' => "### Política de Privacidade Dearly\n\nO Dearly foi concebido sobre o princípio inegociável de que correspondências íntimas devem permanecer estritamente particulares.\n\n- Jamais vendemos, alugamos ou monetizamos suas cartas pessoais ou dados de perfil.\n- Não exibimos seu e-mail pessoal, sua conta do Google ou seu nome civil para os demais usuários.\n- Todas as cartas são criptografadas em trânsito e isoladas estritamente entre o remetente e o destinatário autorizado.\n- Você pode exportar seus dados ou solicitar a exclusão definitiva da sua conta a qualquer instante nas configurações.",
                'type' => 'string'
            ],

            // Base Policy Defaults — Terms of Service
            [
                'key' => 'terms_of_service_en',
                'value' => "### Dearly Terms of Service\n\nBy accessing Dearly, you agree to:\n\n- Be at least 18 years of age.\n- Use the platform solely for peaceful, personal correspondence.\n- Abide by community guidelines and daily postal sending limits.\n- Respect recipient privacy and refrain from automated scraping or bulk unsolicited dispatch.",
                'type' => 'string'
            ],
            [
                'key' => 'terms_of_service_bn',
                'value' => "### ডিয়ারলি ব্যবহারের শর্তাবলী\n\nডিয়ারলি ব্যবহার করার মাধ্যমে আপনি সম্মত হচ্ছেন যে:\n\n- আপনার বয়স কমপক্ষে ১৮ বছর হতে হবে।\n- প্ল্যাটফর্মটি কেবল শান্ত ও ব্যক্তিগত পত্রবিনিময়ের জন্য ব্যবহার করবেন।\n- কমিউনিটি নির্দেশিকা ও দৈনিক চিঠি পাঠানোর নিয়ম মেনে চলবেন।\n- প্রাপকের গোপনীয়তাকে সম্মান করবেন এবং স্বয়ংক্রিয় স্ক্র্যাপিং বা অনাকাঙ্ক্ষিত গণ-বার্তা পাঠানো থেকে বিরত থাকবেন।",
                'type' => 'string'
            ],
            [
                'key' => 'terms_of_service_hi',
                'value' => "### डियरली सेवा की शर्तें\n\nडियरली का उपयोग करके, आप निम्नलिखित पर सहमति व्यक्त करते हैं:\n\n- आपकी आयु कम से कम १८ वर्ष होनी चाहिए।\n- प्लेटफ़ॉर्म का उपयोग केवल शांतिपूर्ण और व्यक्तिगत पत्राचार के लिए किया जाएगा।\n- समुदाय दिशानिर्देशों और दैनिक डाक प्रेषण सीमाओं का पालन करेंगे।\n- प्राप्तकर्ता की गोपनीयता का सम्मान करेंगे और स्वचालित स्क्रैपिंग या अनचाहे बल्क संदेश भेजने से दूर रहेंगे।",
                'type' => 'string'
            ],
            [
                'key' => 'terms_of_service_ar',
                'value' => "### شروط خدمة ديرلي\n\nمن خلال دخولك واستخدامك لمنصة ديرلي، فإنك توافق على الالتزام بما يلي:\n\n- أن يكون عمرك ۱۸ عاماً على الأقل.\n- استخدام المنصة حصرياً للمراسلات الودية والشخصية الهادئة.\n- الالتزام التام بإرشادات المجتمع والحدود اليومية المحددة لإرسال البريد.\n- احترام خصوصية الطرف الآخر والامتناع التام عن استخدام أدوات الكشط الآلي أو إرسال الرسائل الجماعية غير المرغوب فيها.",
                'type' => 'string'
            ],
            [
                'key' => 'terms_of_service_es',
                'value' => "### Términos de Servicio de Dearly\n\nAl acceder y utilizar Dearly, aceptas comprometerte a:\n\n- Tener al menos 18 años de edad cumplidos.\n- Emplear la plataforma con el único propósito de mantener correspondencia personal, pacífica y reflexiva.\n- Acatar fielmente las directrices comunitarias y los límites postales diarios de envío.\n- Respetar la intimidad de los destinatarios y abstenerse de recopilación automatizada de datos o envíos masivos no deseados.",
                'type' => 'string'
            ],
            [
                'key' => 'terms_of_service_fr',
                'value' => "### Conditions Générales d'Utilisation de Dearly\n\nEn accédant à Dearly et en utilisant ses services, vous convenez de :\n\n- Être âgé d'au moins 18 ans révolus.\n- Utiliser la plateforme dans le seul dessein d'une correspondance personnelle, pacifique et authentique.\n- Vous conformer à la charte communautaire et aux plafonds quotidiens d'envoi postal.\n- Respecter la sphère privée de vos destinataires et vous abstenir de tout archivage automatisé ou démarchage massif non sollicité.",
                'type' => 'string'
            ],
            [
                'key' => 'terms_of_service_pt',
                'value' => "### Termos de Serviço Dearly\n\nAo acessar e usufruir do Dearly, você concorda com o seguinte:\n\n- Ter idade mínima de 18 anos.\n- Utilizar a plataforma exclusivamente para correspondência mútua, pacífica e afetuosa.\n- Cumprir integralmente as diretrizes comunitárias e os limites diários de despacho de correspondências.\n- Respeitar o espaço e a privacidade dos destinatários, abstendo-se de coletas automatizadas de dados ou envios em massa.",
                'type' => 'string'
            ],
        ];

        // Seed base fallback keys
        $settings[] = ['key' => 'about_heading', 'value' => $settings[array_search('about_heading_en', array_column($settings, 'key'))]['value'], 'type' => 'string'];
        $settings[] = ['key' => 'about_badge', 'value' => $settings[array_search('about_badge_en', array_column($settings, 'key'))]['value'], 'type' => 'string'];
        $settings[] = ['key' => 'about_content', 'value' => $settings[array_search('about_content_en', array_column($settings, 'key'))]['value'], 'type' => 'string'];
        $settings[] = ['key' => 'about_postmaster_title', 'value' => $settings[array_search('about_postmaster_title_en', array_column($settings, 'key'))]['value'], 'type' => 'string'];
        $settings[] = ['key' => 'about_postmaster_sub', 'value' => $settings[array_search('about_postmaster_sub_en', array_column($settings, 'key'))]['value'], 'type' => 'string'];
        $settings[] = ['key' => 'about_postal_mark', 'value' => $settings[array_search('about_postal_mark_en', array_column($settings, 'key'))]['value'], 'type' => 'string'];
        $settings[] = ['key' => 'how_it_works_badge', 'value' => $settings[array_search('how_it_works_badge_en', array_column($settings, 'key'))]['value'], 'type' => 'string'];
        $settings[] = ['key' => 'how_it_works_heading', 'value' => $settings[array_search('how_it_works_heading_en', array_column($settings, 'key'))]['value'], 'type' => 'string'];
        $settings[] = ['key' => 'how_it_works_steps', 'value' => $settings[array_search('how_it_works_steps_en', array_column($settings, 'key'))]['value'], 'type' => 'json'];
        $settings[] = ['key' => 'how_it_works_cta_text', 'value' => $settings[array_search('how_it_works_cta_text_en', array_column($settings, 'key'))]['value'], 'type' => 'string'];
        $settings[] = ['key' => 'faqs', 'value' => $settings[array_search('faqs_en', array_column($settings, 'key'))]['value'], 'type' => 'json'];
        $settings[] = ['key' => 'community_guidelines', 'value' => $settings[array_search('community_guidelines_en', array_column($settings, 'key'))]['value'], 'type' => 'string'];
        $settings[] = ['key' => 'privacy_policy', 'value' => $settings[array_search('privacy_policy_en', array_column($settings, 'key'))]['value'], 'type' => 'string'];
        $settings[] = ['key' => 'terms_of_service', 'value' => $settings[array_search('terms_of_service_en', array_column($settings, 'key'))]['value'], 'type' => 'string'];

        foreach ($settings as $setting) {
            ApplicationSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
