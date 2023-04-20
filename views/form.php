<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css"/>
<?php
    $found = false;
    $searched = false;
    $certificate_id = $_GET['certificate_id'];
    if ($certificate_id) {
        $searched = true;
        if ($certificate_id > 0) {
            $certificate = get_certificate($certificate_id)[0];
            if (!is_null($certificate->id)) {
                $found = true;
            }
        }
    }
?>
<div class="container p-5">
    <div class="row">
        <div class="col-4">
            <h1>
                <?php echo esc_html__('Verify Certificate', 'certificate-check'); ?>
            </h1>
            <p style="margin: 1rem 0!important;">
                <?php echo esc_html__('Enter a certificates number to verify', 'certificate-check'); ?>
            </p>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <form method="POST" action="<?php echo esc_html(admin_url('admin-post.php')); ?>" class="mb-3" id="verify-form">
                <input type="hidden" name="action" value="certificate_check_query">
                <?php wp_nonce_field('certificate_check_query', 'certificate_check_admin'); ?>
                <input type="hidden" name="redirectToUrl" value="<?php echo rtrim(get_permalink(), '/'); ?>">
                <div class="row">
                    <div class="col-md-4">
                        <label for="certificate_number" class="form-label fs-4">
                            Certificate Number
                            <sup><small><i class="bi bi-asterisk text-danger"></i></small></sup>
                        </label>
                        <input type="text" class="form-control" id="certificate_number" name="certificate_number" placeholder="ABC12345" autocomplete="certificate_number" value="<?php echo $certificate->certificate_number ?>" value="<?php echo $certificate->certificate_number?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <button class="btn btn-dark">Check Certificate</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php
        if ($searched && !$found) {
            echo "<div class='row'><div class='col-md-4'><div id='message' class='alert alert-danger'>Certificate Not Found</div></div></div>";
        } elseif ($searched && $found) {
            echo "<div class='row'><div class='col-md-4'><div id='message' class='alert alert-success'>Certificate Verified, Congrats!!</div></div></div>";
        }
    ?>
    <div class="row justify-content-center mt-5 <?php if(!$found) {echo 'd-none';} ?>" id='certificate'>
        <div class="col-12">
            <h2>Certificate Details</h2>
            <table id="cetificate" class="table table-striped">
                <tr>
                    <td class="text-capitalize">Exam Date</td>
                    <td class="text-capitalize"><?php echo $certificate->exam_date?></td>
                </tr>
                <tr>
                    <td class="text-capitalize">Exam Result</td>
                    <td class="text-capitalize"><?php echo $certificate->result?></td>
                </tr>
                <tr>
                    <td class="text-capitalize">Field of Study</td>
                    <td class="text-capitalize"><?php echo $certificate->product?></td>
                </tr>
                <tr>
                    <td class="text-capitalize">Issue Date</td>
                    <td class="text-capitalize"><?php echo $certificate->issue_date?></td>
                </tr>
                <tr>
                    <td class="text-capitalize">Certificate Number</td>
                    <td class="text-capitalize"><?php echo $certificate->certificate_number?></td>
                </tr>
                <tr>
                    <td class="text-capitalize">Delegate First Name</td>
                    <td class="text-capitalize"><?php echo $certificate->first_name?></td>
                </tr>
                <tr>
                    <td class="text-capitalize">Delegate Last Name</td>
                    <td class="text-capitalize"><?php echo $certificate->last_name?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js">