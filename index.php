<?php session_start();

spl_autoload_register('autoloader');

function autoloader($class){
	include("$class.php");
}

$db = new Db('my_sqlite_db.db');

//inserting data
if( isset($_POST['ADD_STUDENT']) ){
	$name = $_POST['name'];
	$email = $_POST['email'];
	if( $db->insert($name, $email) ){
		$msg = array('1', "Student added successfully.");
	}else{
		$msg = array('0', "Sorry, student adding failed.");
	}
	$_SESSION['msg'] = $msg;
}

//geting edit items
if( isset($_REQUEST['edit']) ){
	$id = $_GET['id'];
	$data = $db->getById($id)->fetchArray();
}

//updateing data
if( isset($_POST['UPDATE_STUDENT']) ){
	$id = $_POST['id'];
	$name = $_POST['name'];
	$email = $_POST['email'];
	if( $db->update($id, $name, $email) ){
		$msg = array('1', "Student updated successfully.");
	}else{
		$msg = array('0', "Sorry, student updating failed.");
	}
	$_SESSION['msg'] = $msg;
	header("Location: /");
	exit;
}

//deleting data
if( isset($_REQUEST['delete']) ){
	$id = $_GET['id'];
	if( $db->delete($id) ){
		$msg = array('1', "Student deleted successfully.");
	}else{
		$msg = array('0', "Sorry, student deleting failed.");
	}
	$_SESSION['msg'] = $msg;

	header("Location: /");
	exit;
}


// to show updating form
$isEdit = isset($_REQUEST['edit']) ? true : false;

?>

<!DOCTYPE html>
<html>
<head>
	<title>CRUD with Sqlite</title>
	<style type="text/css">
		.red { color: red; }
		.green { color: green; }
	</style>
</head>
<body>
	<div style="margin: 0 auto; width: 800px;">
		<div>
			<form style="display:<?php echo $isEdit ? 'none':'block'; ?>" action="" method="post">
				<input type="text" name='name' placeholder="Enter name">
				<input type="email" name='email' placeholder="Enter email" required="">
				<input type="submit" name="ADD_STUDENT" value="Add">
			</form>

			<form style="display:<?php echo $isEdit ? 'block':'none'; ?>" action="" method="post">
				<input type="hidden" name="id" value="<?php echo isset($data) ? $data['rowid'] : ''; ?>">
				<input type="text" name='name' value="<?php echo isset($data) ? $data['name'] : ''; ?>" placeholder="Enter name">
				<input type="email" name='email' value="<?php echo isset($data) ? $data['email'] : ''; ?>" placeholder="Enter email" required="">
				<input type="submit" name="UPDATE_STUDENT" value="Save">
			</form>

			<?php if( isset($_SESSION['msg']) && !empty($_SESSION['msg']) ){ ?>
			<p class="<?php echo $_SESSION['msg'][0]==0 ? 'red' : 'green';?>"><?php echo $_SESSION['msg'][1];?></p>
			<?php } ?>
		</div>
		<table cellpadding="5" border="1" width="100%">
			<tr>
				<td>ID</td>
				<td>Name</td>
				<td>Email</td>
				<td>Action</td>
			</tr>
			<?php 
			$result = $db->getAll();
			//echo "<pre>"; print_r($result->fetchArray());
			//$allData = 
			while($row = $result->fetchArray()) {?>
				
			<tr>
				<td><?php echo $row['rowid'];?></td>
				<td><?php echo $row['name'];?></td>
				<td><?php echo $row['email'];?></td>
				<td>
					<a href="?edit=true&id=<?php echo $row['rowid']; ?>">Edit</a> | 
					<a href="?delete=true&id=<?php echo $row['rowid']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
				</td>
			</tr>
			<?php } ?>

		</table>
	</div>
<?php $_SESSION['msg'] = array(); ?>
</body>	
</html>