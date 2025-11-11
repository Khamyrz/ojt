<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Phase Document Submission</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
	<style>
	.phase-progress { background:#f8f9fa; padding:20px; border-radius:8px; }
	.progress { height:25px; border-radius:12px; }
	.progress-bar { background: linear-gradient(135deg, #457b9d, #1d3557); border-radius:12px; }
	.phase-section { background:#f8f9fa; padding:20px; border-radius:8px; margin-bottom:20px; border-left:4px solid #457b9d; }
	.badge { font-size:.875em; }
	.form-group { margin-bottom:1rem; }
	.header-section { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid #e9ecef; }
	.logout-btn { background: linear-gradient(135deg, #dc3545, #c82333); border: none; border-radius: 8px; padding: 8px 16px; color: white; text-decoration: none; transition: all 0.3s ease; }
	.logout-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3); color: white; }
	.welcome-text { color: #457b9d; font-weight: 600; }
	</style>
</head>
<body>
	<div class="container py-4">
		<div class="row justify-content-center">
			<div class="col-md-10 col-lg-8">
				<!-- Header with Welcome and Logout -->
				<div class="header-section">
					<div>
						<h3 class="welcome-text mb-1">
							<i class="fas fa-user-circle me-2"></i>
							Welcome, {{ $intern->first_name }} {{ $intern->last_name }}
						</h3>
						<p class="text-muted mb-0">Phase Document Submission Portal</p>
					</div>
					<form action="{{ route('intern.logout') }}" method="POST" style="margin: 0;">
						@csrf
						<button type="submit" class="logout-btn">
							<i class="fas fa-sign-out-alt me-1"></i>
							Logout
						</button>
					</form>
				</div>

				@if(session('success'))
					<!-- Success Modal -->
					<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content">
								<div class="modal-header bg-success text-white">
									<h5 class="modal-title" id="successModalLabel"><i class="fas fa-check-circle me-2"></i>Success</h5>
								</div>
								<div class="modal-body text-center">
									<p>{{ session('success') }}</p>
								</div>
								<div class="modal-footer justify-content-center">
									<button type="button" class="btn btn-success" data-bs-dismiss="modal">Okay</button>
								</div>
							</div>
						</div>
					</div>
					<script>
						document.addEventListener("DOMContentLoaded", function() {
							var successModal = new bootstrap.Modal(document.getElementById('successModal'));
							successModal.show();
						});
					</script>
				@endif

				@if(session('error'))
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
						<i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
						<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
					</div>
				@endif

				@php
					$progress = 0;
					switch($intern->current_phase) {
						case 'pre_deployment': $progress = 33; break;
						case 'mid_deployment': $progress = 66; break;
						case 'deployment': $progress = 100; break;
						default: $progress = 0;
					}
				@endphp

				<div class="phase-progress mb-4">
					<div class="progress"><div class="progress-bar" role="progressbar" style="width: {{ $progress }}%">{{ $progress }}%</div></div>
					<div class="mt-2 text-muted small">Current Phase: <strong>{{ ucfirst(str_replace('_', ' ', $intern->current_phase)) }}</strong></div>
				</div>

				@if($intern->current_phase === 'pre_deployment')
					<div class="phase-section">
						<h5><i class="fas fa-file-upload me-2"></i>Pre-Deployment Documents</h5>
						@php $prePending = $intern->pre_deployment_status === 'pending'; $preEmpty = is_null($intern->pre_deployment_status) || empty($intern->resume); @endphp
						@if($preEmpty)
							<form action="{{ route('intern.submit.pre-deployment') }}" method="POST" enctype="multipart/form-data">
								@csrf
								<div class="form-group">
									<label><i class="fas fa-file-alt me-1"></i>Resume</label>
									<input type="file" name="resume" accept=".pdf,.doc,.docx" class="form-control" required>
									<small class="text-muted">PDF or DOC/DOCX (any size)</small>
								</div>
								<div class="form-group">
									<label><i class="fas fa-envelope me-1"></i>Application Letter</label>
									<input type="file" name="application_letter" accept=".pdf,.doc,.docx" class="form-control" required>
									<small class="text-muted">PDF or DOC/DOCX (any size)</small>
								</div>
								<div class="form-group">
									<label><i class="fas fa-heartbeat me-1"></i>Medical Certificate</label>
									<input type="file" name="medical_certificate" accept=".pdf,.doc,.docx" class="form-control" required>
									<small class="text-muted">PDF or DOC/DOCX (any size)</small>
								</div>
								<div class="form-group">
									<label><i class="fas fa-shield-alt me-1"></i>Insurance</label>
									<input type="file" name="insurance" accept=".pdf,.doc,.docx" class="form-control" required>
									<small class="text-muted">PDF or DOC/DOCX (any size)</small>
								</div>
								<div class="form-group">
									<label><i class="fas fa-check-circle me-1"></i>Acceptance Letter (Auto-generated)</label>
									<div class="border rounded p-2" style="background:#fff">
										<iframe src="{{ route('intern.acceptance') }}" style="width:100%; height:400px; border:0;" title="Acceptance Letter Preview"></iframe>
									</div>
									<div class="mt-2">
										<a href="{{ route('intern.acceptance') }}" target="_blank" class="btn btn-outline-primary btn-sm">
											<i class="fas fa-external-link-alt me-1"></i>Open Acceptance Letter in New Tab
										</a>
									</div>
								</div>
								<div class="form-group">
									<label><i class="fas fa-stamp me-1"></i>Notarized Parent's Waiver</label>
									<input type="file" name="parents_waiver" accept=".pdf,.doc,.docx" class="form-control" required>
									<small class="text-muted">PDF or DOC/DOCX (any size)</small>
								</div>
								<button type="submit" class="btn btn-primary">
									<i class="fas fa-upload me-1"></i>Submit Pre-Deployment Documents
								</button>
							</form>
						@elseif($prePending)
							<div class="alert alert-info mb-0">
								<i class="fas fa-clock me-2"></i>
								<strong>Wait for Acceptance:</strong> Your Pre-Deployment documents are being reviewed by the admin.
							</div>
						@endif
					</div>
				@endif

				@if($intern->current_phase === 'mid_deployment')
					<div class="phase-section">
						<h5><i class="fas fa-handshake me-2"></i>Mid-Deployment Documents</h5>
						@php $midPending = $intern->mid_deployment_status === 'pending'; $midEmpty = is_null($intern->mid_deployment_status) || empty($intern->memorandum_of_agreement); @endphp
						@if($midEmpty)
							<form action="{{ route('intern.submit.mid-deployment') }}" method="POST" enctype="multipart/form-data">
								@csrf
								<div class="form-group">
									<label><i class="fas fa-file-contract me-1"></i>Memorandum of Agreement (Auto-generated)</label>
									<div class="border rounded p-2" style="background:#fff">
										<iframe src="{{ route('intern.memorandum') }}" style="width:100%; height:400px; border:0;" title="MOA Preview"></iframe>
									</div>
									<div class="mt-2">
										<a href="{{ route('intern.memorandum') }}" target="_blank" class="btn btn-outline-primary btn-sm">
											<i class="fas fa-external-link-alt me-1"></i>Open MOA in New Tab
										</a>
									</div>
								</div>
								<div class="form-group">
									<label><i class="fas fa-file-signature me-1"></i>Internship Contract (Auto-generated)</label>
									<div class="border rounded p-2" style="background:#fff">
										<iframe src="{{ route('intern.contract') }}" style="width:100%; height:400px; border:0;" title="Contract Preview"></iframe>
									</div>
									<div class="mt-2">
										<a href="{{ route('intern.contract') }}" target="_blank" class="btn btn-outline-primary btn-sm">
											<i class="fas fa-external-link-alt me-1"></i>Open Contract in New Tab
										</a>
									</div>
								</div>
								<button type="submit" class="btn btn-primary">
									<i class="fas fa-upload me-1"></i>Submit Mid-Deployment Documents
								</button>
							</form>
						@elseif($midPending)
							<div class="alert alert-info mb-0">
								<i class="fas fa-clock me-2"></i>
								<strong>Wait for Acceptance:</strong> Your Mid-Deployment documents are being reviewed by the admin.
							</div>
						@endif
					</div>
				@endif

				@if($intern->current_phase === 'deployment')
					<div class="phase-section">
						<h5><i class="fas fa-rocket me-2"></i>Deployment Documents</h5>
						@php $depPending = $intern->deployment_status === 'pending'; $depEmpty = is_null($intern->deployment_status) || empty($intern->recommendation_letter); @endphp
						@if($depEmpty)
							<form action="{{ route('intern.submit.deployment') }}" method="POST" enctype="multipart/form-data">
								@csrf
								<div class="form-group">
									<label><i class="fas fa-star me-1"></i>Recommendation Letter</label>
									<input type="file" name="recommendation_letter" accept=".pdf,.doc,.docx" class="form-control" required>
									<small class="text-muted">PDF or DOC/DOCX (any size)</small>
								</div>
								<div class="form-group">
									<label><i class="fas fa-file-alt me-1"></i>Endorsement Letter (Auto-generated)</label>
									<div class="border rounded p-2" style="background:#fff">
										<iframe src="{{ route('intern.endorsement') }}" style="width:100%; height:400px; border:0;" title="Endorsement Preview"></iframe>
									</div>
									<div class="mt-2">
										<a href="{{ route('intern.endorsement') }}" target="_blank" class="btn btn-outline-primary btn-sm">
											<i class="fas fa-external-link-alt me-1"></i>Open Endorsement in New Tab
										</a>
									</div>
								</div>
								<button type="submit" class="btn btn-primary">
									<i class="fas fa-upload me-1"></i>Submit Deployment Documents
								</button>
							</form>
						@elseif($depPending)
							<div class="alert alert-info mb-0">
								<i class="fas fa-clock me-2"></i>
								<strong>Wait for Acceptance:</strong> Your Deployment documents are being reviewed by the admin.
							</div>
						@endif
					</div>
				@endif

				@if($intern->current_phase === 'completed')
					<div class="phase-section">
						<div class="alert alert-success mb-0">
							<h5 class="mb-1"><i class="fas fa-trophy me-2"></i>🎉 Congratulations!</h5>
							<p class="mb-2">You have completed all phases. You now have full access to the dashboard.</p>
							<a href="{{ route('intern.dashboard') }}" class="btn btn-success">
								<i class="fas fa-tachometer-alt me-1"></i>Go to Dashboard
							</a>
						</div>
					</div>
				@endif
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<script>
		// Auto-refresh functionality to check for phase status updates
		(function() {
			// Store initial phase status
			let lastPhaseStatus = {
				current_phase: '{{ $intern->current_phase }}',
				pre_deployment_status: '{{ $intern->pre_deployment_status }}',
				mid_deployment_status: '{{ $intern->mid_deployment_status }}',
				deployment_status: '{{ $intern->deployment_status }}',
				status: '{{ $intern->status }}'
			};

			// Function to check phase status
			async function checkPhaseStatus() {
				try {
					const response = await fetch('{{ route("intern.check-phase-status") }}', {
						method: 'GET',
						headers: {
							'Accept': 'application/json',
							'X-Requested-With': 'XMLHttpRequest'
						},
						credentials: 'same-origin'
					});

					if (!response.ok) {
						throw new Error('Failed to check phase status');
					}

					const data = await response.json();

					// Check if any phase status has changed
					const hasChanged = 
						data.current_phase !== lastPhaseStatus.current_phase ||
						data.pre_deployment_status !== lastPhaseStatus.pre_deployment_status ||
						data.mid_deployment_status !== lastPhaseStatus.mid_deployment_status ||
						data.deployment_status !== lastPhaseStatus.deployment_status ||
						data.status !== lastPhaseStatus.status;

					if (hasChanged) {
						// Phase status has changed, reload the page
						console.log('Phase status updated. Reloading page...');
						window.location.reload();
					}
				} catch (error) {
					console.error('Error checking phase status:', error);
					// Silently fail - don't interrupt user experience
				}
			}

			// Check phase status every 5 seconds
			const checkInterval = setInterval(checkPhaseStatus, 5000);

			// Also check when page becomes visible (user switches back to tab)
			document.addEventListener('visibilitychange', function() {
				if (!document.hidden) {
					checkPhaseStatus();
				}
			});

			// Clean up interval when page is unloaded
			window.addEventListener('beforeunload', function() {
				clearInterval(checkInterval);
			});
		})();
	</script>
</body>
</html>
