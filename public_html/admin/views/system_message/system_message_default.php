<?php

$query = "SELECT * FROM system_message ORDER BY system_message_id DESC ";
$numrows = $db_handle->numRows($query);

$rowsperpage = 20;

$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {
   $currentpage = (int) $_GET['pg'];
} else {
   $currentpage = 1;
}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }

$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }

$offset = ($currentpage - 1) * $rowsperpage;
$query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$system_message = $db_handle->fetchAssoc($result);

?>

<p>Manage system messages below.</p>
                                
<table class="table table-responsive table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if(isset($system_message) && !empty($system_message)) { foreach ($system_message as $row) { ?>
        <tr>
            <td><?php echo $row['description']; ?></td>
            <td>
                <a title="Edit" class="btn btn-info" href="system_message.php?action=update&id=<?php echo encrypt($row['system_message_id']); ?>"><i class="glyphicon glyphicon-edit icon-white"></i> Edit</a>
                <a title="Send Test" class="btn btn-info" href="system_message.php?action=test&id=<?php echo encrypt($row['system_message_id']); ?>"><i class="fa fa-paper-plane fa-fw"></i> </a>
            </td>
        </tr>
        <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
    </tbody>
</table>

<?php if(isset($system_message) && !empty($system_message)) { ?>
<div class="tool-footer text-right">
    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
</div>
<?php } ?>

<?php if(isset($system_message) && !empty($system_message)) { require_once 'layouts/pagination_links.php'; } ?>