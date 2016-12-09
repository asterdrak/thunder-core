<?php
namespace ThunderCore;

/**
* the class needs to have session and user doctrine models created, with correct properties
*/
class Session implements \SplSubject
{

  /**
   * store current session entity
   */
  private $session;

  /**
   * stores current session user
   */
  private $user;

  /**
   * store bool value which tells if current session is established
   * firstly is set to false
   * if start method can continue session - it is set to true
   * if session is now created it is set to true
   * if session is not established - user is not logged in it stays false
   */
  private $session_check = false;

  /**
   * stores bool values which says if session was started (start method)
   * if wasn't we shouldn't use any session methods
   */
  private $started = false;

  /**
   * stores bool values which says if user was verified (first step of session creation)
   */
  private $user_verified = false;

  /**
   * current session token from start method
   */
  private $token = null;


  /**
   * method which re-starts session (takes COOKIE's and re-establish current session)
   * it sets session_check property to true if session is established
   */
  public function start()
  {

    $this->started = true;

    $this->set_models_objects();

    if(array_key_exists('Token', $_COOKIE))
      $this->token = $_COOKIE['Token'];
    else return false;

    $session = $this->Session->find(['token' => $this->token]);

    if($session != null && $session->active && $session->expires_at > new \DateTime()) {

      $expires_at = new \DateTime(); $expires_at->setTimestamp(time()+10*24*3600);
      $session->expires_at = $expires_at;
      if($session->save()) {
        $this->user = $session->user;
        setcookie("Token", $session->token, time()+10*24*3600, '/', NULL, 0);
        $this->set_session_check(true);
      }
      else
        throw new \Exception("Session renew failed", 1);
    }
  }

  /**
  * first step of session creation, it takes array with user as param in format:
  * Array ( [email] => john.doe@random.com [password] => example_pass )
  * and verifies if user data is right (return true if so, in other case returns false)
  */
  public function verify_user($user_array) {
    $this->check_if_started();
    $this->user_verified = true;

    $this->user = $this->User->find(['email' => $user_array['email']]);
    if($this->user == null) return false;
    if($this->user->password != hash("sha256", $this->user->randomizer . md5($user_array['password'])))
      return false;

    return true;
  }

  /**
  * creates new record in sessions table, creates cookie with token and start current_session
  */
  public function create() {
    $this->check_if_started();
    $this->check_if_user_verified();

    $session = $this->Session->new();
    $session->user = $this->user;
    $session->token = md5( md5($this->user->password . $this->user->email) . microtime() . rand());
    $session->active = 1;

    $expires_at = new \DateTime(); $expires_at->setTimestamp(time()+10*24*3600);
    $session->expires_at = $expires_at;

    if($session->save()) {
      setcookie("Token", $session->token, time()+10*24*3600, '/', NULL, 0);
      $this->set_session_check(true);
      return true;
    }
    else
      throw new \Exception("Session creation failed", 1);

  }

  /**
  * function destroys current session
  */
  public function destroy() {
    $this->check_if_started();

    $session = $this->Session->find(['token' => $this->token]);

    if(empty($session)) throw new \Exception("No session to destroy", 1);

    $session->active = 0;

    if($session->save()) {
      setcookie("Token", null, time(), '/', NULL, 0);
      $this->set_session_check(false);
      return true;
    }
    else
      throw new \Exception("Session destroy failed", 1);

  }

  /**
  * checks if current session is established, return true if so, in other case returns false
  */
  public function check() {
    return $this->session_check && $this->started;
  }

  public function __get($property) {
    if (property_exists($this, $property)) {
        return $this->$property;
    }
  }


  // ----------------------------------------
  //                SPL OBSERWER
  // ----------------------------------------

  private $observers = [];


  public function attach(\SplObserver $observer) {
      $this->observers[] = $observer;
  }

  public function detach(\SplObserver $observer) {
      $key = array_search($observer,$this->observers, true);
      if($key){
          unset($this->observers[$key]);
      }
  }

  public function notify() {
      foreach ($this->observers as $value) {
          $value->update($this);
      }
  }


  // ----------------------------------------
  //            PRIVATE METHODS
  // ----------------------------------------

  private function set_models_objects() {
    $this->Session = new ModelWrapper('Session');
    $this->User = new ModelWrapper('User');
  }

  /**
  * check if start method was called (should be before any session method is called)
  */
  private function check_if_started() {
    if (!$this->started) {
      throw new \Exception("Before use any Session method you need to start session object first", 1);
    }
  }

  /**
  * throws exception if we didn't call verify_user but we are trying create session
  */
  private function check_if_user_verified() {
    if(!$this->user_verified || empty($this->user)) {
      throw new \Exception("You have to verify user before creating new session", 1);
    }
  }

  /**
   * sends notify to observers
   * changes session_check value
   */
  private function set_session_check(bool $var) {
    $this->session_check = $var;
    $this->notify();
  }
}