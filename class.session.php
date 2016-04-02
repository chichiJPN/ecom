<?php 
class MySessionHandler implements SessionHandlerInterface
{
	private $savePath;

    public function open($savePath, $sessionName)
    {
		echo $savePath . ' '. $sessionName;
        return true;
    }

    public function close()
    {
		echo 'close';
        return true;
    }

    public function read($id)
    {
		echo '<br>'.$id;
		echo '<br>'.session_id();
		print_r($_SESSION);
		$string = '';
		if(isset($_SESSION['user_session'])) {
			echo 'there is a session';
			// try {
				// $userID = $_SESSION['user_session'];
				// $stmt = $conn->prepare("SELECT sessionData
										// FROM sessions
										// WHERE UserID=:userID
										// LIMIT 1
										// ");
				
				// if($stmt->execute(array(':userID' => $userID)) && $stmt->rowCount() > 0)
				// {
					// if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
						// $string = $row['sessionData'];
					// }
				// }
			// } catch(PDOException $e) {
			// }finally {
				// $stmt=null;
			// }
		}
		
		
        // return (string)@file_get_contents("$this->savePath/sess_$id");
        return $string;
    }

    public function write($id, $data)
    {
		echo $data;
		echo 'write';
		$flag = false;
		
		if(isset($_SESSION['user_session'])) {
			try {
				$userID = $_SESSION['user_session'];
				
				$stmt = $conn->prepare("UPDATE sessions
										SET sessionData=:sessionData
										WHERE UserID=:userID
										");
				
				// if($stmt->execute(array(':sessionData' => $data,':userID' => $userID)) && $stmt->rowCount() > 0)
				// {
					// if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
						// $string = $row['sessionData'];
					// }
				// }
			} catch(PDOException $e) {
			}finally {
				$stmt=null;
			}
		}
		
		return $flag;
    }

    public function destroy($id)
    {
		echo 'destroy';
        $file = "$this->savePath/sess_$id";
        if (file_exists($file)) {
            unlink($file);
        }

        return true;
    }

    public function gc($maxlifetime)
    {
        foreach (glob("$this->savePath/sess_*") as $file) {
            if (filemtime($file) + $maxlifetime < time() && file_exists($file)) {
                unlink($file);
            }
        }

        return true;
    }
}

?>