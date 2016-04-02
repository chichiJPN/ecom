<?php 
class USER
{
   function __construct(){}

   public function is_loggedin()
   {
      if(isset($_SESSION['user_session'])) {
         return true;
      } else {
		  return false;
	  }
   }
 
   public function redirect($url)
   {
       header("Location: $url");
   }
 
   public function logout()
   {
        session_destroy();
        unset($_SESSION['user_session']);
        return true;
   }
}
?>