<?php

namespace App\Model;

/**
 * Class CommentsRepository
 * @package App\Model
 */
class CommentsRepository extends BaseRepository 
{

	/** @var CommentsMapper */
	private $mapper;

	/**
	 * CommentsRepository constructor.
	 * @param CommentsMapper $mapper
	 */
	public function __construct(CommentsMapper $mapper) {
		parent::__construct($mapper);
		$this->mapper = $mapper;
	}

	/**
	 * @return array
	 */
	public function getAllComments() {
		$allComments = $this->mapper->getAll();
		$comments = [];
		foreach($allComments as $comment) {
			$comments[] = [
				'id' => $comment->id,
				'articleId' => $comment->articleId,
				'date' => $comment->date,
				'author' => $comment->author,
				'text' => $comment->text,
				'allowed' => $comment->allowed,
			];
		}
		return $comments;
	}
}
