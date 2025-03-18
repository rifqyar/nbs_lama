<?php
$db = getDB("storage");
$no_cont = $_POST["NO_CONT"];
$query = "SELECT
	*
FROM
	(
	SELECT
		a.*,
		b.TGL_REQUEST
	FROM
		CONTAINER_RECEIVING a
	LEFT JOIN REQUEST_RECEIVING b ON
		a.NO_REQUEST = b.NO_REQUEST
	WHERE
		a.NO_CONTAINER = '$no_cont'
	ORDER BY
		b.TGL_REQUEST DESC ) a
		WHERE rownum =1";
// $q = $db->query("SELECT * FROM CONTAINER_RECEIVING WHERE NO_CONTAINER = '$no_cont' ORDER BY TABLEIDX DESC");
$q = $db->query($query);
$r = $q->getAll();
?>

<div id="cont">
    <table class="form-input" style="margin: 30px 30px 30px 30px;" border="1">
        <thead>
            <tr>
                <th>NO_CONTAINER</th>
                <th>NO_REQUEST</th>
                <th>STATUS</th>
                <th>AKTIF</th>
                <th>Change Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($r)) : ?>
                <?php foreach ($r as $row) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['NO_CONTAINER']) ?></td>
                        <td><?= htmlspecialchars($row['NO_REQUEST']) ?></td>
                        <td><?= htmlspecialchars($row['STATUS']) ?></td>
                        <td>
                            <select class="select2-aktif" id="aktif-<?= htmlspecialchars($row['NO_CONTAINER']) ?>-<?= htmlspecialchars($row['NO_REQUEST']) ?>">
                                <option value="Y" <?= $row['AKTIF'] === 'Y' ? 'selected' : '' ?>>Y</option>
                                <option value="T" <?= $row['AKTIF'] === 'T' ? 'selected' : '' ?>>T</option>
                            </select>
                            <span>(Active Existing: <?= htmlspecialchars($row['AKTIF']) ?>)</span>
                        </td>
                        <td>
                            <button onclick="changeAktifStatus('<?= $row['NO_CONTAINER'] ?>', '<?= $row['NO_REQUEST'] ?>')">Update AKTIF</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5">No data available</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
