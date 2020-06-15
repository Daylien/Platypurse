<?php

namespace Model;

use Hydro\Base\Database\Driver\SQLite;
use Hydro\Base\Model\BaseModel;

class UserModel extends BaseModel
{
    private $id;
    private $displayName;
    private $mail;
    private $password;
    private $ugId;
    private $rating;
    private $createdAt;
    private $disabled;

    /**
     * UserModel constructor.
     * @param $id
     * @param $displayName
     * @param $mail
     * @param $password
     * @param $ugId
     * @param $rating
     * @param $createdAt
     * @param $disabled
     */
    public function __construct($id, $displayName, $mail, $password, $ugId, $rating, $createdAt, $disabled)
    {
        $this->id = $id;
        $this->displayName = $displayName;
        $this->mail = $mail;
        $this->password = $password;
        $this->ugId = $ugId;
        $this->rating = $rating;
        $this->createdAt = $createdAt;
        $this->disabled = $disabled;
        parent::__construct();
    }

    public static function getFromDatabase($preparedWhereClause = "", $values = array(),
                                           $groupClause = "", $orderClause = "", $limitClause = "") {
        $user = array();
        $result = SQLite::selectBuilder(COLUMNS_USER,
            TABLE_USER,
            $preparedWhereClause,
            $values,
            $groupClause,
            $orderClause,
            $limitClause);

        foreach ($result as $row):
            $user[] = new UserModel($row[COLUMNS_USER["u_id"]],
                $row[COLUMNS_USER["display_name"]],
                $row[COLUMNS_USER["mail"]],
                $row[COLUMNS_USER["password"]],
                $row[COLUMNS_USER["ug_id"]],
                $row[COLUMNS_USER["rating"]],
                $row[COLUMNS_USER["created_at"]],
                $row[COLUMNS_USER["disabled"]]);
        endforeach;

        if(sizeof($user) <= 1):
            return array_shift($user);
        else:
            return $user;
        endif;
    }

    public function writeToDatabase() {
        // Check if offer exists in database
        $userInDatabase = $this->getFromDatabase(COLUMNS_OFFER["u_id"]. " = ?"
            , array($this->getId()));

        // If platypus doesn't exist, insert into database. Else update in database
        if(empty($userInDatabase)):
            return $this->insertIntoDatabase();
        else:
            return $this->updateInDatabase();
        endif;
    }

    /**
     * @return bool
     */
    public function insertIntoDatabase() {
        return SQLite::insertBuilder(TABLE_USER,
            COLUMNS_USER,
            $this->getDatabaseValues());
    }

    /**
     *
     */
    public function updateInDatabase() {
        $preparedSetClause = "";
        foreach (COLUMNS_USER as $tableCol):
            $preparedSetClause .= $tableCol. " = ?,";
        endforeach;

        $preparedWhereClause = COLUMNS_USER["u_id"]. " = " .$this->getId();

        return SQLite::updateBuilder(TABLE_USER,
            substr($preparedSetClause, 0, -1),
            $preparedWhereClause,
            $this->getDatabaseValues());
    }

    /**
     *
     */
    public function deleteFromDatabase() {
        return SQLite::deleteBuilder(TABLE_USER,
            COLUMNS_USER['u_id']. " = ?;",
            array($this->getId()));
    }

    /**
     * @return array
     */
    public function getDatabaseValues() {
        return array($this->getId(),
            $this->getDisplayName(),
            $this->getMail(),
            $this->getPassword(),
            $this->getUgId(),
            $this->getRating(),
            $this->getCreatedAt(),
            $this->isDisabled());
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param mixed $displayName
     */
    public function setDisplayName($displayName): void
    {
        $this->displayName = $displayName;
    }

    /**
     * @return mixed
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param mixed $mail
     */
    public function setMail($mail): void
    {
        $this->mail = $mail;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getUgId()
    {
        return $this->ugId;
    }

    /**
     * @param mixed $ugId
     */
    public function setUgId($ugId): void
    {
        $this->ugId = $ugId;
    }

    /**
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->ugId == 1;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating): void
    {
        $this->rating = $rating;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function isDisabled()
    {
        return $this->disabled;
    }

    /**
     * @param mixed $disabled
     */
    public function setDisabled($disabled): void
    {
        $this->disabled = $disabled;
    }
}
