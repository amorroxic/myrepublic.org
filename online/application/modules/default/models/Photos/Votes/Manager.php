<?php

class Photos_Votes_Manager
{
	
	public $votesTable;
	public $userData;
	public $minimumVotes;
	public $averageVote;

	public function __construct ()
	{
		$this->votesTable = new Photos_Votes_Table();
		$this->userData = Zend_Auth::getInstance()->getStorage()->read();
		$this->minimumVotes = 5;
	}
	
	public function isVoteCasted($photo_id = null) {
		if (!isset($this->userData)) return false;
		if (!isset($photo_id)) return false;
		$select = $this->votesTable->select();
		$select->where('photo_id = ?', $photo_id);
		$select->where('user_id = ?', $this->userData->id);
		$records = $this->votesTable->fetchAll($select);
		$records = $records->toArray();
		if (count($records)>0) {
			return true;
		} else {
			return false;
		}
		
	}
	
	public function getVoteCount($photoID = null) {
		if (!isset($photoID)) return 0;
		$select = $this->votesTable->select();
		$select->where('photo_id = ?', $photoID);
		$records = $this->votesTable->fetchAll($select);
		return count($records);
	}

	public function addVote($photo_id = null,$vote=null) {

			if (!isset($photo_id)) return false;
			if (!isset($vote)) return false;

			$statement = array(
			   'photo_id'=>$photo_id,
			   'vote'=>$vote,
			   'user_id'=>$this->userData->id
			);
			
			$this->votesTable->insert($statement);

			//toate notele din sistem
			$select = $this->votesTable->select();
			$records = $this->votesTable->fetchAll($select);
			$votes = $records->toArray();

			//membrii din sistem
			$siteUsers = new User_Table();
			$select = $siteUsers->select();
			$records = $siteUsers->fetchAll($select);
			$users = $records->toArray();
			
			$totalVotes = count($votes);
			if ($totalVotes<1) $totalVotes = 1;
			$totalUsers = count($users);
			$averageVotes = $totalVotes / $totalUsers;
			
			$sum = 0;
			foreach ($votes as $vote) {
				$sum += $vote["vote"];
			}
			$averageRankPerSite = $sum / $totalVotes;
			
			$select = $this->votesTable->select();
			$select->from($this->votesTable, array('user_id', 'count(id) as voturi'));
			$select->group('user_id');
			$userVotes = $this->votesTable->fetchAll($select)->toArray();
			
			foreach ($userVotes as $userVote) {
				$userVoteRatio = $userVote["voturi"] / $averageVotes;
 
				$statement = array(
					'vote_ratio' =>$userVoteRatio
				);
				$where = "id = " . $userVote["user_id"];
				$siteUsers->update($statement,$where);
				
			}
			
/*
	 		SELECT v.photo_id, sum( v.vote * u.vote_ratio ) / sum( vote_ratio ) AS average
			FROM user_photo_votes v
			INNER JOIN site_users u ON v.user_id = u.id
			GROUP BY v.photo_id
*/
	
			$db = Zend_Registry::get('dbAdapter');
			$select = $db->select()
			             ->from(array('v' => 'user_photo_votes'),array('v.photo_id', 'vote_count'=>'count(*)', 'average' => 'sum( v.vote * u.vote_ratio ) / sum( vote_ratio )'))
			             ->joinInner(array('u' => 'site_users'),'v.user_id = u.id',array());
			$select->group('v.photo_id');
			$photos = $db->fetchAll($select);
			
			foreach ($photos as $photo) {
			
				$minimumVotesRequired = 5;
				$votes = $photo["vote_count"];
				$rank = (round($photo["average"], 2) * ($votes/($votes + $minimumVotesRequired))) + ($averageRankPerSite * ($minimumVotesRequired/($votes + $minimumVotesRequired)));
				
				$userPhotos = new Photos_Table();
				$statement = array(
					'photo_rank' =>$rank
				);
				$where = "id = " . $photo["photo_id"];
				$userPhotos->update($statement,$where);
							
			}
			
			
			return true;
		
	}


}