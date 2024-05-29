<table class="table">
    <thead>
        <tr>
            <th>
                שם קובץ
            </th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($objects['Contents'] as $object) { ?>
            <tr>
                <td><?= $object['Key'] ?></td>
                <td class="d-flex">
                    <a class="btn btn-link btn-sm" href="<?= $s3->getPresignedObjectUrl($object['Key']) ?>" target="_blank" class="aws-s3-link">פתח</a>
                    <form action="" method="post" class="ms-2">
                        <input type="hidden" name="delete_file">
                        <input type="hidden" name="key" value="<?= $object['Key'] ?>">
                        <button type="submit" class="btn btn-link btn-sm">מחק</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>