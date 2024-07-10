<?php

namespace quiz;

use Exception;

class DBFactory
{

    /**
     * @throws Exception
     */
    public function createQuizQuestion(string $questionText, string $categoryText, array $answers): int
    {
        if ($this->getIdByText($questionText, KindOf::QUESTION->getDBHandler()) !== 0) throw new Exception('Frage existert bereits');

        $categoryId = $this->createIdText($categoryText, KindOf::CATEGORY);
        $questionId = $this->createQuestion($categoryId, $questionText);

        foreach ($answers as $key => $answer) {
            $answerId = $this->createIdText($key, KindOf::ANSWER);
            $this->createRelation($questionId, $answerId, $answer);
        }

        return $questionId;
    }

    private function createRelation(int $questionId, int $answerId, bool $isRight): void
    {
        KindOf::RELATION->getDBHandler()->create([
            'question_id' => $questionId,
            'answer_id' => $answerId,
            'is_Right' => $isRight ? 1 : 0
        ]);
    }

    private function createIdText(string $text, KindOf $kindOf): int
    {
        $id = $this->getIdByText($text, $kindOf->getDBHandler());
        return $id > 0 ? $id : $kindOf->getDBHandler()->create(['text' => $text]);
    }


    private function createQuestion($categoryId, $text): int
    {
        return KindOf::QUESTION->getDBHandler()->create([
            'category_id' => $categoryId,
            'user_id' => $_SESSION['UserId'],
            'text' => $text
        ]);
    }

    private function getIdByText(string $searchText, CanHandleDB $handler): int
    {
        $id = 0;
        $items = $handler->findAll();
        foreach ($items as $item) {
            if (strtoupper($item['text']) === strtoupper(trim($searchText))) $id = $item['id'];
            if ($id !== 0) break;
        }
        return $id;
    }

    /**
     * @throws Exception
     */

    public function createUser(string $username, string $email, string $password): int
    {
        $userData = KindOf::USER->getDBHandler()->findAll();
        foreach ($userData as $user) {
            if ($user['email'] === trim($email)) throw new Exception('User exists for this email already');
        }
        $pwHash = password_hash($password,PASSWORD_BCRYPT);
        return KindOf::USER->getDBHandler()->create([
            'username' => $username,
            'email' => $email,
            'password' => $pwHash
        ]);
    }

    public function createCategory(string $text): int
    {
        return $this->createIdText($text, KindOf::CATEGORY);
    }

}