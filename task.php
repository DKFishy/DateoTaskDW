<?

public function manageUsers($users, $action)
{
	foreach ($users as $user) {
		if{
			if($action == 'update'){
				$errormmsg = 'We couldn\'t update user: ';
				$successmsg = 'All users updated.';
			}
			if($action == 'store'){
				$erromsg = 'We couldn\'t store user: ';
				$successmsg = 'All users created.';
			}
		}
		try {
			if ($user['name'] && $user['login'] && $user['email'] && $user['password'] && strlen($user['name']) >= 10)
			{DB::table('users')->where('id', $user['id'])->$action([
					'name' => $user['name'],
					'login' => $user['login'],
					'email' => $user['email'],
					'password' => PASSWORD_HASH($user['password'], PASSWORD_DEFAULT)
			]);}
		} catch (Throwable $e) {
			return Redirect::back()->withErrors(['error', [$errormsg $e->getMessage()]]);
		}
	}
	if($action == 'store'){
		$this->sendEmail($users);
	}
	return Redirect::back()->with(['success', $successmsg]);
}

private function sendEmail($users)
{
	foreach ($users as $user) {
		$message = 'Account has beed created. You can log in as <b>' . $user['login'] . '</b>';
			if ($user['email']) {
				Mail::to($user['email'])
				->cc('support@company.com')
				->subject('New account created')
				->queue($message);
	}
}
	return true;
}

?>
