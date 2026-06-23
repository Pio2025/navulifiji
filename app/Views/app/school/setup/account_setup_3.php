<!-- Add these simple CSS styles for consistency -->
<style>
/* Consistent error styling for both sections */
.stream-tag-card {
    transition: all 0.3s ease;
    cursor: pointer;
    border: 2px solid transparent;
}

.stream-tag-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.stream-tag-card.active {
    border-color: #009ef7;
    background-color: rgba(0, 158, 247, 0.05);
}

.stream-tag-card.invalid {
    border-color: #f1416c;
    background-color: rgba(241, 65, 108, 0.05);
}

.stream-tag-card input[type="radio"] {
    display: none;
}

/* Consistent with level cards */
.level-card {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.level-card.invalid {
    background-color: rgba(241, 65, 108, 0.05);
    border-color: #f1416c;
}

.level-card.valid {
    background-color: rgba(80, 205, 137, 0.05);
    border-color: #50cd89;
}

/* Stream select styling */
.stream-select-wrapper .form-select.is-invalid {
    border-color: #f1416c;
    background-color: rgba(241, 65, 108, 0.05);
}

.stream-select-wrapper .form-select.is-valid {
    border-color: #50cd89;
    background-color: rgba(80, 205, 137, 0.05);
}

/* Required indicator */
.required-indicator {
    color: #f1416c;
}

/* Badge for stream count */
.badge-stream-count {
    font-size: 0.75rem;
    padding: 3px 8px;
    border-radius: 10px;
}

/* Simple shake animation for errors */
.error-shake {
    animation: shake 0.5s ease-in-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

/* Responsive */
@media (max-width: 768px) {
    .level-card {
        padding: 10px;
    }
    
    .stream-tag-card {
        margin-bottom: 15px;
    }
}
</style>

<?= $this->include('app/school/setup/toolbar') ?>

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
        
        <?= $this->include('app/school/setup/navbar') ?>
        
        <!-- Flash messages container -->
        <div id="flash-messages-container">
            <?= $this->include('templates/flash_messages') ?>
        </div>
        
        <!-- Validation summary -->
        <div id="validation-summary" class="mb-5"></div>
        
        <!--begin::Basic info-->
        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Step 4 - School Stream Configuration</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->
            <!--begin::Content-->
            <div id="kt_account_settings_profile_details" class="collapse show">
                <!--begin::Form-->
                <form id="kt_account_profile_details_form" class="form" method="post" action="<?= site_url('school/configure') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="current_step" value="3" id="current_step">
                    
                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">
                        
                        <!-- Info Alert -->
                        <div class="alert alert-info d-flex align-items-center p-5 mb-8">
                            <i class="ki-duotone ki-information fs-2hx text-info me-4">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            <div class="d-flex flex-column">
                                <h4 class="mb-1 text-info">Stream Configuration Required</h4>
                                <span class="mb-2">Configure how student streams will be identified throughout your Navuli account. This setting affects class labeling, reporting, and student organization.</span>
                                <small class="text-muted">Note: All levels must have at least one stream configured.</small>
                            </div>
                        </div>
                        
                        <!-- Stream Tagging Section -->
                        <?php 
                        $streamTagError = session()->getFlashdata('errors')['stream_tag'] ?? 
                                         (isset($validation) ? $validation->getError('stream_tag') : '');
                        $hasStreamTagError = !empty($streamTagError);
                        ?>
                        
                        <div class="mb-10">
                            <h3 class="fw-semibold badge-light-primary mb-6" style="width:100%;padding:15px;">
                                <i class="ki-duotone ki-tag me-2 fs-4"></i>
                                Stream Identification Method
                                <span class="required-indicator">*</span>
                            </h3>
                            
                            <div class="row g-6 mb-3">
                                <!-- Numeric Option -->
                                <div class="col-lg-6">
                                    <div class="stream-tag-card card h-100 <?= (old('stream_tag') == 'numeric') ? 'active' : '' ?> 
                                        <?= $hasStreamTagError ? 'invalid error-shake' : '' ?>"
                                        onclick="selectStreamTag('numeric')">
                                        <div class="card-body p-5 text-center">
                                            <div class="mb-4">
                                                <span class="symbol symbol-50px symbol-circle bg-primary bg-opacity-10">
                                                    <span class="symbol-label fs-2x fw-bold text-primary">123</span>
                                                </span>
                                            </div>
                                            <h4 class="mb-3">Numeric Tagging</h4>
                                            <p class="text-muted mb-4">
                                                Streams will be labeled numerically (Year 101, Year 102, Year 103)
                                            </p>
                                            <div class="form-check d-inline-block">
                                                <input class="form-check-input" type="radio" name="stream_tag" 
                                                    id="stream_tag_numeric" value="numeric" 
                                                    <?= (old('stream_tag') == 'numeric') ? 'checked' : '' ?>>
                                                <label class="form-check-label fw-bold" for="stream_tag_numeric">
                                                    Select Numeric
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Alphabetic Option -->
                                <div class="col-lg-6">
                                    <div class="stream-tag-card card h-100 <?= (old('stream_tag') == 'alphabetic') ? 'active' : '' ?> 
                                        <?= $hasStreamTagError ? 'invalid error-shake' : '' ?>"
                                        onclick="selectStreamTag('alphabetic')">
                                        <div class="card-body p-5 text-center">
                                            <div class="mb-4">
                                                <span class="symbol symbol-50px symbol-circle bg-success bg-opacity-10">
                                                    <span class="symbol-label fs-2x fw-bold text-success">ABC</span>
                                                </span>
                                            </div>
                                            <h4 class="mb-3">Alphabetic Tagging</h4>
                                            <p class="text-muted mb-4">
                                                Streams will be labeled alphabetically (Year 1A, Year 1B, Year 1C)
                                            </p>
                                            <div class="form-check d-inline-block">
                                                <input class="form-check-input" type="radio" name="stream_tag" 
                                                    id="stream_tag_alphabetic" value="alphabetic" 
                                                    <?= (old('stream_tag') == 'alphabetic') ? 'checked' : '' ?>>
                                                <label class="form-check-label fw-bold" for="stream_tag_alphabetic">
                                                    Select Alphabetic
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Stream Tag Error Display -->
                            <?php if ($hasStreamTagError): ?>
                                <div class="alert alert-danger d-flex align-items-center p-4 mt-3">
                                    <i class="ki-duotone ki-shield-cross fs-2hx text-danger me-3"></i>
                                    <div class="d-flex flex-column">
                                        <span><?= esc($streamTagError) ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <hr class="my-8 text-gray-300">
                        
                        <!-- Stream Configuration Section -->
                        <div class="mb-10">
                            <h3 class="fw-semibold badge-light-primary mb-6" style="width:100%;padding:15px;">
                                <i class="ki-duotone ki-layer-group me-2 fs-4"></i>
                                Stream Configuration by Level
                                <span class="required-indicator">*</span>
                            </h3>
                            
                            <p class="text-muted mb-6">
                                Configure the number of streams for each school level. All levels must have at least one stream.
                            </p>
                            
                            <?php 
                            $streamsError = session()->getFlashdata('errors')['streams'] ?? 
                                          (isset($validation) ? $validation->getError('streams') : '');
                            $levelErrors = session()->getFlashdata('errors')['levels'] ?? [];
                            $hasStreamsError = !empty($streamsError);
                            ?>
                            
                            <div class="row g-4" id="streams-configuration">
                                <?php 
                                $counter = 0;
                                foreach($level as $row): 
                                    $levelId = $row['level_id'];
                                    $levelName = esc($row['level_name']);
                                    $oldValue = old("streams.{$counter}") ?? '';
                                    $hasError = isset($levelErrors[$levelId]) || ($hasStreamsError && empty($oldValue));
                                ?>
                                <div class="col-xl-4 col-lg-4 col-md-6">
                                    <div class="level-card <?= $hasError ? 'invalid' : (!empty($oldValue) ? 'valid' : '') ?>">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="fw-bold mb-0"><?= $levelName ?></h5>
                                            <?php if (!empty($oldValue)): ?>
                                                <span class="badge badge-stream-count bg-success">
                                                    <?= $oldValue ?> stream<?= $oldValue > 1 ? 's' : '' ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="stream-select-wrapper">
                                            <select class="form-select <?= $hasError ? 'is-invalid' : (!empty($oldValue) ? 'is-valid' : '') ?>" 
                                                    name="streams[]" 
                                                    data-level-id="<?= $levelId ?>"
                                                    data-level-name="<?= $levelName ?>"
                                                    onchange="updateLevelCard(this)">
                                                <option value="">Select number of streams</option>
                                                <?php for($i = 1; $i <= 10; $i++): ?>
                                                    <option value="<?= $i ?>" <?= $oldValue == $i ? 'selected' : '' ?>>
                                                        <?= $i ?> stream<?= $i > 1 ? 's' : '' ?>
                                                    </option>
                                                <?php endfor; ?>
                                            </select>
                                            
                                            <?php if (isset($levelErrors[$levelId])): ?>
                                                <div class="invalid-feedback d-block mt-2">
                                                    <?= esc($levelErrors[$levelId]) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                $counter++;
                                endforeach; 
                                ?>
                            </div>
                            
                            <!-- Streams Error Display -->
                            <?php if ($hasStreamsError): ?>
                                <div class="alert alert-danger d-flex align-items-center p-4 mt-5">
                                    <i class="ki-duotone ki-shield-cross fs-2hx text-danger me-3"></i>
                                    <div class="d-flex flex-column">
                                        <h5 class="mb-2">Stream Configuration Error</h5>
                                        <span><?= esc($streamsError) ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                    </div>
                    <!--end::Card body-->
                    
                    <!--begin::Actions-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <button type="reset" class="btn btn-light btn-active-light-primary me-3" onclick="resetForm()">Reset</button>
                        <button type="submit" class="btn btn-primary" id="submit-btn">
                            <span class="indicator-label">Save Configuration</span>
                            <span class="indicator-progress">
                                Processing... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Basic info-->
    </div>
    <!--end::Content container-->
</div>
<!--end::Content-->

<!-- Simplified JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Function to select stream tag
    window.selectStreamTag = function(value) {
        // Uncheck all radio buttons
        document.querySelectorAll('input[name="stream_tag"]').forEach(radio => {
            radio.checked = false;
        });
        
        // Remove active and invalid classes from all cards
        document.querySelectorAll('.stream-tag-card').forEach(card => {
            card.classList.remove('active', 'invalid', 'error-shake');
        });
        
        // Check selected radio
        const radioToCheck = document.querySelector(`input[name="stream_tag"][value="${value}"]`);
        if (radioToCheck) {
            radioToCheck.checked = true;
            radioToCheck.closest('.stream-tag-card').classList.add('active');
        }
    };
    
    // Function to update level card styling
    window.updateLevelCard = function(selectElement) {
        const card = selectElement.closest('.level-card');
        const value = selectElement.value;
        
        // Remove all styling classes
        card.classList.remove('invalid', 'valid');
        selectElement.classList.remove('is-invalid', 'is-valid');
        
        // Add appropriate styling
        if (value === '') {
            card.classList.add('invalid');
            selectElement.classList.add('is-invalid');
        } else {
            card.classList.add('valid');
            selectElement.classList.add('is-valid');
            
            // Update badge
            const badge = card.querySelector('.badge-stream-count');
            if (badge) {
                badge.textContent = value + ' stream' + (value > 1 ? 's' : '');
                badge.className = 'badge badge-stream-count bg-success';
            } else {
                const titleDiv = card.querySelector('h5').parentElement;
                const newBadge = document.createElement('span');
                newBadge.className = 'badge badge-stream-count bg-success';
                newBadge.textContent = value + ' stream' + (value > 1 ? 's' : '');
                titleDiv.appendChild(newBadge);
            }
        }
    };
    
    // Function to reset form
    window.resetForm = function() {
        // Reset stream tag
        document.querySelectorAll('input[name="stream_tag"]').forEach(radio => {
            radio.checked = false;
        });
        document.querySelectorAll('.stream-tag-card').forEach(card => {
            card.classList.remove('active', 'invalid', 'error-shake');
        });
        
        // Reset stream selects
        document.querySelectorAll('select[name="streams[]"]').forEach(select => {
            select.value = '';
            select.classList.remove('is-invalid', 'is-valid');
            const card = select.closest('.level-card');
            if (card) {
                card.classList.remove('invalid', 'valid');
                const badge = card.querySelector('.badge-stream-count');
                if (badge) {
                    badge.remove();
                }
            }
        });
    };
    
    // Initialize level cards on page load
    document.querySelectorAll('select[name="streams[]"]').forEach(select => {
        if (select.value !== '') {
            updateLevelCard(select);
        }
    });
    
    // Form validation before submit
    document.getElementById('kt_account_profile_details_form').addEventListener('submit', function(e) {
        let isValid = true;
        const errors = [];
        
        // Check stream tag
        const streamTag = document.querySelector('input[name="stream_tag"]:checked');
        if (!streamTag) {
            isValid = false;
            errors.push('Please select a stream tagging method (Numeric or Alphabetic)');
            
            // Add invalid class to both cards
            document.querySelectorAll('.stream-tag-card').forEach(card => {
                card.classList.add('invalid', 'error-shake');
            });
        }
        
        // Check all level streams
        const emptyStreams = [];
        document.querySelectorAll('select[name="streams[]"]').forEach((select, index) => {
            if (select.value === '') {
                isValid = false;
                const levelName = select.getAttribute('data-level-name') || `Level ${index + 1}`;
                emptyStreams.push(levelName);
                
                // Highlight empty selects
                select.classList.add('is-invalid');
                select.closest('.level-card').classList.add('invalid');
            }
        });
        
        if (emptyStreams.length > 0) {
            errors.push(`Please configure streams for: ${emptyStreams.join(', ')}`);
        }
        
        // Show validation errors
        if (!isValid) {
            e.preventDefault();
            
            // Remove existing validation summary
            const existingSummary = document.getElementById('validation-summary');
            if (existingSummary) {
                existingSummary.innerHTML = '';
            }
            
            // Create validation summary
            const summaryDiv = document.createElement('div');
            summaryDiv.className = 'alert alert-danger p-5 mb-5';
            summaryDiv.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="ki-duotone ki-shield-cross fs-2hx text-danger me-4">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-dark">Configuration Required</h4>
                        <p class="mb-2">Please fix the following issues:</p>
                        <ul class="mb-0">
                            ${errors.map(error => `<li>${error}</li>`).join('')}
                        </ul>
                    </div>
                </div>
            `;
            
            // Insert in validation summary container
            const validationContainer = document.getElementById('validation-summary');
            if (validationContainer) {
                validationContainer.appendChild(summaryDiv);
            }
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            // Show loading state
            const submitBtn = document.getElementById('submit-btn');
            submitBtn.classList.add('disabled');
            submitBtn.querySelector('.indicator-label').style.display = 'none';
            submitBtn.querySelector('.indicator-progress').style.display = 'inline-block';
        }
    });
    
    // Auto-select stream tag cards on page load
    const selectedStreamTag = document.querySelector('input[name="stream_tag"]:checked');
    if (selectedStreamTag) {
        selectStreamTag(selectedStreamTag.value);
    }
    
});
</script>