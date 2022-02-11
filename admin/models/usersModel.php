<?php
class users extends database
{
    public $id;
    public $profilePicture;
    public $userName;
    public $email;
    public $password;
    public $registerDate;
    public $id_roles;
    protected $db;

    public function __construct()
    {
        $this->db = parent::__construct();
    }

    public function addUser()
    {
        $query = 'INSERT INTO `s4u3u_users`(`userName`,`email`,`password`,registerDate,`id_roles`) VALUES (:userName,:email,:password,NOW()),2)';
        $queryPrepare = $this->db->prepare($query);
        $queryPrepare->bindValue(':userName', $this->userName, PDO::PARAM_STR);
        $queryPrepare->bindValue(':email', $this->email, PDO::PARAM_STR);
        $queryPrepare->bindValue(':password', $this->password, PDO::PARAM_STR);
        return $queryPrepare->execute();
    }

    public function getUsersList()
    {
        $query = 'SELECT user.id,userName,email,roles.name,count(opi.id) AS nbrOpinions,COUNT(CASE WHEN rep.id_users = user.id THEN rep.id_users END) AS nbrReportsTaken,COUNT(CASE WHEN rep.id_users_write = user.id THEN rep.id_users_write END) AS nbrReportsGifted,count(offer.id) AS nbrOffer '
            . 'FROM `s4u3u_users` AS user '
            . 'INNER JOIN `s4u3u_roles` AS roles '
            . 'ON user.id_roles = roles.id '
            . 'LEFT JOIN `s4u3u_opinions` AS opi '
            . 'ON user.id = opi.id_users '
            . 'LEFT JOIN `s4u3u_offers` As offer '
            . 'ON user.id = offer.id  '
            . 'LEFT JOIN `s4u3u_reports` AS rep '
            . 'ON user.id IN (rep.id_users,rep.id_users_write) '
            . 'GROUP BY user.id ';
        $queryExecute = $this->db->query($query);
        return $queryExecute->fetchAll(PDO::FETCH_OBJ);
    }

    public function deleteUser()
    {
        $query = 'DELETE FROM `s4u3u_users` WHERE id=:id';
        $queryPrepare = $this->db->prepare($query);
        $queryPrepare->bindValue(':id', $this->id, PDO::PARAM_INT);
        return $queryPrepare->execute();
    }

    public function updateUser()
    {
        $query = 'UPDATE `s4u3u_users` SET `profilePicture` = :profilePicture,`userName` = :userName,`email` = :email  WHERE id=1';
        $queryPrepare = $this->db->prepare($query);
        $queryPrepare->bindValue(':userName', $this->userName, PDO::PARAM_STR);
        $queryPrepare->bindValue(':profilePicture', $this->profilePicture, PDO::PARAM_STR);
        $queryPrepare->bindValue(':email', $this->email, PDO::PARAM_STR);
        return $queryPrepare->execute();
    }

    public function getUser()
    {
        $query = 'SELECT `profilePicture`,`userName`,`email` FROM `s4u3u_users` WHERE id=1';
        $queryExecute = $this->db->query($query);
        return $queryExecute->fetch(PDO::FETCH_OBJ);
    }

    public function getNewUsersList()
    {
        $query = 'SELECT user.id,userName,email,roles.name,count(opi.id) AS nbrOpinions,COUNT(CASE WHEN rep.id_users = user.id THEN rep.id_users END) AS nbrReportsTaken '
            . 'FROM `s4u3u_users` AS user '
            . 'INNER JOIN `s4u3u_roles` AS roles '
            . 'ON user.id_roles = roles.id '
            . 'LEFT JOIN `s4u3u_opinions` AS opi '
            . 'ON user.id = opi.id_users '
            . 'LEFT JOIN `s4u3u_reports` AS rep '
            . 'ON user.id = rep.id_users '
            . 'GROUP BY user.id '
            . 'ORDER BY user.id DESC '
            . 'LIMIT 3 ';
        $queryExecute = $this->db->query($query);
        return $queryExecute->fetchAll(PDO::FETCH_OBJ);
    }
    public function mailDouble()
    {
        $query = 'SELECT   COUNT(email) AS emailCount
        FROM    s4u3u_users
        WHERE email = :email';
        $queryPrepare = $this->db->prepare($query);
        $queryPrepare->bindValue(':email', $this->email, PDO::PARAM_STR);
        $queryPrepare->execute();
        $result = $queryPrepare->fetch(PDO::FETCH_OBJ);
        return $result->emailCount;
    }
    
    public function userNameDouble()
    {
        $query = 'SELECT   COUNT(userName) AS userNameCount
        FROM    s4u3u_users
        WHERE userName = :userName'; 
        $queryPrepare = $this->db->prepare($query);
        $queryPrepare->bindValue(':userName', $this->userName, PDO::PARAM_STR);
        $queryPrepare->execute();
        $result = $queryPrepare->fetch(PDO::FETCH_OBJ);
        return $result->userNameCount;
    }
}