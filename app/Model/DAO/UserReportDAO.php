<?php
namespace Model\DAO;

use Hydro\Base\Contracts\ReportDAOInterface;
use PDOException;

class UserReportDAO implements ReportDAOInterface
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }


    public function create($obj)
    {
        $query = "INSERT INTO user_reports (ur_id, reported_u_id, reporter_u_id, rr_id, message)
            VALUES (:id, :reportedUserId, :reporterUserId, :reportReasonId, :message);";
        $stmt = $this->con->prepare($query);
        $stmt->bindValue(":id", $obj->getId());
        $stmt->bindValue(":reportedUserId", $obj->getReportedObject()->getId());
        $stmt->bindValue(":reporterUserId", $obj->getReporterUser()->getId());
        // TODO: Implement report reason to match use in frontend
        $stmt->bindValue(":reportReasonId", $obj->getReportReason()[0]);
        $stmt->bindValue(":message", $obj->getUserId());

        if($stmt->execute()) {
            $id = $this->con->lastInsertId();
            $sql = "SELECT * FROM user_reports WHERE ur_id = $id;";
            $result = $this->con->query($sql);
            return $result->fetch();
        } else {
            throw new PDOException('UserReportDAO create error');
        }
    }

    public function read($id)
    {
        $sql = "SELECT * FROM user_reports
                WHERE ur_id = :id;";
        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(":id", $id);

        if($stmt->execute()) {
            return $stmt->fetchAll();
        } else {
            throw new PDOException('UserReportDAO read error');
        }
    }

    public function readAll()
    {
        $sql = "SELECT * FROM user_reports;";

        $stmt = $this->con->prepare($sql);

        if($stmt->execute()) {
            return $stmt->fetchAll();
        } else {
            throw new PDOException('UserReportDAO readAll error');
        }
    }

    public function update($obj)
    {
        $sql = "UPDATE user_reports SET active = :active WHERE ur_id = :id;";
        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(":active", $obj->isActive());
        $stmt->bindValue(":id", $obj->getId());


        if($stmt->execute()) {
            return true;
        } else {
            throw new PDOException('UserReportDAO update error');
        }
    }
}