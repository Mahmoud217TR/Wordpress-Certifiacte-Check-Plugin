
<h1><?php echo esc_html__('Edit a Certificate', 'certificate-check'); ?></h1>
<p><?php echo esc_html__('edit the certificates', 'certificate-check'); ?></p>
<?php
    $certificate = get_certificate($_GET['id'])[0];
    $found = !is_null($certificate->id);

    if (!$found) {
        echo "<div class='row'><div class='col-md-4'><div id='message' class='alert alert-danger'>Certificate Not Found</div></div></div>";
    }
?>

<form method="POST" action="<?php echo esc_html(admin_url('admin-post.php')); ?>" class="mb-3 <?php if (!$found) { echo 'd-none';} ?>">
    <input type="hidden" name="action" value="certificate_check_admin_update">
    <?php wp_nonce_field('certificate_check_admin_update', 'certificate_check_admin'); ?>
    <input type="hidden" name="redirectToUrl" value="<?php echo certificate_check_admin_view_pagename(''); ?>">
    <input type="hidden" name="id" value="<?php echo $certificate->id ?>">
    <div class="row g-3">
        <div class="col-md-4 ">
            <label for="certificate_number" class="form-label">
                Certificate Number
                <sup><small><i class="bi bi-asterisk text-danger"></i></small></sup>
            </label>
            <input type="text" class="form-control" id="certificate_number" name="certificate_number" placeholder="ABC12345" autocomplete="certificate_number" value="<?php echo $certificate->certificate_number ?>" required>
        </div>
        <div class="col-md-4 ">
            <label for="first_name" class="form-label">
                First Name
                <sup><small><i class="bi bi-asterisk text-danger"></i></small></sup>
            </label>
            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Jhon" autocomplete="first_name" value="<?php echo $certificate->first_name ?>" required>
        </div>
        <div class="col-md-4 ">
            <label for="last_name" class="form-label">
                Last Name
                <sup><small><i class="bi bi-asterisk text-danger"></i></small></sup>
            </label>
            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Doe" autocomplete="last_name" value="<?php echo $certificate->last_name ?>" required>
        </div>
        <div class="col-md-4 ">
            <label for="email" class="form-label">
                Email
                <sup><small><i class="bi bi-asterisk text-danger"></i></small></sup>
            </label>
            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" autocomplete="email" value="<?php echo $certificate->email ?>" required>
        </div>
        <div class="col-md-4 ">
            <label for="mobile" class="form-label">
                Mobile
                <sup><small><i class="bi bi-asterisk text-danger"></i></small></sup>
            </label>
            <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="(912) 345-67-89" autocomplete="mobile" value="<?php echo $certificate->mobile ?>" required>
        </div>
        <div class="col-md-4 ">
            <label for="product" class="form-label">
                Product
                <sup><small><i class="bi bi-asterisk text-danger"></i></small></sup>
            </label>
            <input type="text" class="form-control" id="product" name="product" placeholder="MSC in Computer Science" autocomplete="product" value="<?php echo $certificate->product ?>" required>
        </div>
        <div class="col-md-4 ">
            <label for="issue_date" class="form-label">
                Issue Date
                <sup><small><i class="bi bi-asterisk text-danger"></i></small></sup>
            </label>
            <input type="date" class="form-control" id="issue_date" name="issue_date" autocomplete="issue_date" value="<?php echo $certificate->issue_date ?>" required>
        </div>
        <div class="col-md-4 ">
            <label for="exam_date" class="form-label">
                Exam Date
                <sup><small><i class="bi bi-asterisk text-danger"></i></small></sup>
            </label>
            <input type="date" class="form-control" id="exam_date" name="exam_date" autocomplete="exam_date" value="<?php echo $certificate->exam_date ?>" required>
        </div>
        <div class="col-md-4 ">
            <label for="result" class="form-label">
                Result
                <sup><small><i class="bi bi-asterisk text-danger"></i></small></sup>
            </label>
            <input type="text" class="form-control" id="result" name="result" placeholder="Pass" autocomplete="result" value="<?php echo $certificate->result ?>" required>
        </div>
        <div class="col-md-4">
            <button class="btn btn-dark">Update Certificate</button>
        </div>
    </div>
</form>

