<?php

use Illuminate\Database\Seeder;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('questions')->insert([
            [
                'quiz_id'       => '1',
                'question'      => 'Stereotypes are useful as they allow us to categorize lots of information easily. Rigid stereotypes about people generally lead to prejudice.  Stereotyping is considered as ',
                'answer_type'   => 'mcq',
                'answer1'       => 'An affective process',
                'answer2'       => 'An action',
                'answer3'       => 'A cognitive process',
                'answer4'       => 'An evaluation process',
                'answer5'       => '',
                'correct_answer'=> 'A cognitive process',
            ],[
                'quiz_id'       => '1',
                'question'      => 'Highly prejudiced people tend to have what is referred to by psychologists as an authoritarian personality. Which one of the following is not considered as one of the characteristics of authoritarian personality:',
                'answer_type'   => 'mcq',
                'answer1'       => 'A strong commitment to conform to prevailing structures',
                'answer2'       => 'Extremely respectful of authority',
                'answer3'       => 'Intolerant of weakness in themselves and others',
                'answer4'       => 'Affiliation orientation',
                'answer5'       => '',
                'correct_answer'=> 'Affiliation orientation',
            ],[
                'quiz_id'       => '1',
                'question'      => 'We may learn to be prejudiced from home, school, government, workplace, place of worship, and the media. Which of the following is related to the media?',
                'answer_type'   => 'mcq',
                'answer1'       => 'promoting a learning environment that focuses only on one value-system and not discussing other value systems positively',
                'answer2'       => 'imbalanced coverage of minority communities and typically concentrating on criminal activities',
                'answer3'       => 'not passing down equal rights legislation that promotes fairness and equality for diverse groups',
                'answer4'       => 'imposing a glass ceiling that blocks nearly all minorities and women from top positions',
                'answer5'       => '',
                'correct_answer'=> 'imbalanced coverage of minority communities and typically concentrating on criminal activities',
            ],[
                'quiz_id'       => '1',
                'question'      => 'Different people may express prejudice differently. There are people who often disclose outwardly how they are opposed to unequal treatment, but their inner feelings may suggest otherwise. They may say they are egalitarian and use that open display as an excuse when they act in a way that is not in the interests of diversity. This way of expressing prejudice is known as',
                'answer_type'   => 'mcq',
                'answer1'       => 'deny and rationalise',
                'answer2'       => 'act out',
                'answer3'       => 'scapegoat',
                'answer4'       => 'subtly discriminate',
                'answer5'       => '',
                'correct_answer'=> 'subtly discriminate',
            ],[
                'quiz_id'       => '1',
                'question'      => 'Word of mouth is still the most common way for people to learn about hiring and promotion opportunities. Up to 90% of workers find their jobs this way. If you are not a member of the dominant group, it may be difficult for you to learn about opportunities. This is an example of discrimination that happen during the',
                'answer_type'   => 'mcq',
                'answer1'       => 'Recruitment',
                'answer2'       => 'Screening',
                'answer3'       => 'Training and development',
                'answer4'       => 'Performance evaluation',
                'answer5'       => '',
                'correct_answer'=> 'Recruitment',
            ],

            [
                'quiz_id'       => '2',
                'question'      => 'Stereotypes are useful as they allow us to categorize lots of information easily. Rigid stereotypes about people generally lead to prejudice.  Stereotyping is considered as ',
                'answer_type'   => 'mcq',
                'answer1'       => 'An affective process',
                'answer2'       => 'An action',
                'answer3'       => 'A cognitive process',
                'answer4'       => 'An evaluation process',
                'answer5'       => '',
                'correct_answer'=> 'A cognitive process',
            ],[
                'quiz_id'       => '2',
                'question'      => 'Highly prejudiced people tend to have what is referred to by psychologists as an authoritarian personality. Which one of the following is not considered as one of the characteristics of authoritarian personality:',
                'answer_type'   => 'mcq',
                'answer1'       => 'A strong commitment to conform to prevailing structures',
                'answer2'       => 'Extremely respectful of authority',
                'answer3'       => 'Intolerant of weakness in themselves and others',
                'answer4'       => 'Affiliation orientation',
                'answer5'       => '',
                'correct_answer'=> 'Affiliation orientation',
            ],[
                'quiz_id'       => '2',
                'question'      => 'We may learn to be prejudiced from home, school, government, workplace, place of worship, and the media. Which of the following is related to the media?',
                'answer_type'   => 'mcq',
                'answer1'       => 'promoting a learning environment that focuses only on one value-system and not discussing other value systems positively',
                'answer2'       => 'imbalanced coverage of minority communities and typically concentrating on criminal activities',
                'answer3'       => 'not passing down equal rights legislation that promotes fairness and equality for diverse groups',
                'answer4'       => 'imposing a glass ceiling that blocks nearly all minorities and women from top positions',
                'answer5'       => '',
                'correct_answer'=> 'imbalanced coverage of minority communities and typically concentrating on criminal activities',
            ],[
                'quiz_id'       => '2',
                'question'      => 'Different people may express prejudice differently. There are people who often disclose outwardly how they are opposed to unequal treatment, but their inner feelings may suggest otherwise. They may say they are egalitarian and use that open display as an excuse when they act in a way that is not in the interests of diversity. This way of expressing prejudice is known as',
                'answer_type'   => 'mcq',
                'answer1'       => 'deny and rationalise',
                'answer2'       => 'act out',
                'answer3'       => 'scapegoat',
                'answer4'       => 'subtly discriminate',
                'answer5'       => '',
                'correct_answer'=> 'subtly discriminate',
            ],[
                'quiz_id'       => '2',
                'question'      => 'Word of mouth is still the most common way for people to learn about hiring and promotion opportunities. Up to 90% of workers find their jobs this way. If you are not a member of the dominant group, it may be difficult for you to learn about opportunities. This is an example of discrimination that happen during the',
                'answer_type'   => 'mcq',
                'answer1'       => 'Recruitment',
                'answer2'       => 'Screening',
                'answer3'       => 'Training and development',
                'answer4'       => 'Performance evaluation',
                'answer5'       => '',
                'correct_answer'=> 'Recruitment',
            ],
        ]);
    }
}
