<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    // Add custom repository methods if needed
    public function filterBadWords(string $text): string
    {
        // List of bad words to filter
        $badWords = ['badword', 'badword1', 'badword2'];
        $replacement = '*****';

        // Loop through each bad word and replace it using a regular expression
        foreach ($badWords as $badWord) {
            $pattern = '/\b' . preg_quote($badWord, '/') . '\b/i'; // Case-insensitive word boundary matching
            $text = preg_replace($pattern, $replacement, $text);
        }

        return $text;
    }

}
