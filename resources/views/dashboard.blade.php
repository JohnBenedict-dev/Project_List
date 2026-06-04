<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ProjectApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { min-height: 100vh; background-color: #1e3a8a; color: white; width: 260px; position: fixed; transition: all 0.3s; z-index: 1000; }
        .sidebar .nav-link { color: rgba(255,255,255,0.75); padding: 12px 20px; font-weight: 500; display: flex; align-items: center; gap: 10px; border-radius: 8px; margin: 4px 12px; cursor: pointer; transition: all 0.2s ease; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: #3b82f6; color: white; }
        .main-content { margin-left: 260px; padding: 30px; min-height: 100vh; }
        .card-counter { border: none; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .icon-box { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
        .brand-section { padding: 24px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .tab-content-section { display: none; }
        .tab-content-section.active-section { display: block; }
        
        /* Profile Image Styles */
        .profile-avatar-container { position: relative; width: 140px; height: 140px; margin: 0 auto; }
        .profile-avatar { width: 140px; height: 140px; border-radius: 50%; object-fit: cover; border: 4px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.15); }
        .avatar-upload-btn { position: absolute; bottom: 5px; right: 5px; background: #3b82f6; color: white; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 3px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.2); transition: all 0.2s; }
        .avatar-upload-btn:hover { background: #1d4ed8; transform: scale(1.05); }
    </style>
</head>
<body>

    <div class="sidebar d-flex flex-column justify-content-between">
        <div>
            <div class="brand-section d-flex align-items-center gap-2">
                <i class="fa-solid fa-layer-group fs-3 text-info"></i>
                <div>
                    <h5 class="mb-0 fw-bold">ProjectApp</h5>
                    <small class="text-white-50 text-xs">Management System</small>
                </div>
            </div>

            <ul class="nav flex-column mt-3" id="dashboardTabs">
                <li class="nav-item">
                    <a class="nav-link active" data-target="dashboard-tab">
                        <i class="fa-solid fa-chart-pie"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-target="projects-list-tab">
                        <i class="fa-solid fa-list-check"></i> My Project List
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-target="users-tab">
                        <i class="fa-solid fa-users"></i> Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-target="profile-tab">
                        <i class="fa-solid fa-user-gear"></i> My Profile
                    </a>
                </li>
            </ul>
        </div>

        <div class="p-3 border-top border-secondary">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light w-100 d-flex align-items-center justify-content-center gap-2 py-2" style="border-radius: 8px;">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-dark mb-0" id="page-title">Dashboard</h4>
            <div class="dropdown">
                <button class="btn btn-white bg-white border dropdown-toggle px-3 py-2 shadow-sm" style="border-radius: 10px;" type="button" data-bs-toggle="dropdown">
                    @if(Auth::user()->profile_image)
                        <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="User" class="rounded-circle me-1" style="width: 24px; height: 24px; object-fit: cover;">
                    @else
                        <i class="fa-regular fa-user me-2 text-primary"></i> 
                    @endif
                    {{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                    <li><a class="dropdown-menu-item p-2 px-3 text-dark text-decoration-none d-block" href="#" onclick="document.querySelector('[data-target=\'profile-tab\']').click();">Profile Settings</a></li>
                </ul>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert" style="border-radius: 10px;">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert" style="border-radius: 10px;">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div id="dashboard-tab" class="tab-content-section active-section">
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card card-counter p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1 small fw-medium">Total Users</p>
                                <h3 class="fw-bold mb-0 text-dark">{{ $totalUsers }}</h3>
                            </div>
                            <div class="icon-box bg-light text-primary">
                                <i class="fa-solid fa-users-viewfinder"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-counter p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1 small fw-medium">My Total Projects</p>
                                <h3 class="fw-bold mb-0 text-dark">{{ $totalProjects }}</h3>
                            </div>
                            <div class="icon-box bg-primary-subtle text-primary" style="background-color: #dbeafe;">
                                <i class="fa-solid fa-folder-open"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-counter p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1 small fw-medium">Pending Projects</p>
                                <h3 class="fw-bold mb-0 text-dark">{{ $pendingProjects }}</h3>
                            </div>
                            <div class="icon-box bg-warning-subtle text-warning">
                                <i class="fa-regular fa-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-counter p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1 small fw-medium">Completed</p>
                                <h3 class="fw-bold mb-0 text-dark">{{ $completedProjects }}</h3>
                            </div>
                            <div class="icon-box bg-success-subtle text-success">
                                <i class="fa-regular fa-circle-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                        <div class="card-header bg-primary text-white py-3" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                            <h5 class="mb-0 fw-bold"><i class="fa-solid fa-plus-circle me-2"></i>Add New Project</h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('project.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-medium text-secondary">Project Title</label>
                                    <input type="text" class="form-control" name="title" placeholder="Enter project title" required style="border-radius: 8px;">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium text-secondary">Description</label>
                                    <textarea class="form-control" name="description" rows="4" placeholder="Describe your project..." required style="border-radius: 8px;"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium text-secondary">Status</label>
                                    <select class="form-control" name="status" style="border-radius: 8px;">
                                        <option value="Pending">Pending</option>
                                        <option value="In Progress">In Progress</option>
                                        <option value="Completed">Completed</option>
                                    </select>
                                </div>
                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-primary py-2 fw-bold" style="border-radius: 8px;">
                                        <i class="fa-solid fa-floppy-disk me-2"></i>Save Project
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                        <div class="card-header bg-dark text-white py-3" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                            <h5 class="mb-0 fw-bold"><i class="fa-solid fa-folder-open me-2"></i>Recent Work Overview</h5>
                        </div>
                        <div class="card-body p-4 text-center text-muted">
                            <i class="fa-solid fa-chart-line fs-1 mb-3 text-secondary"></i>
                            <h5>Welcome to your Management Hub</h5>
                            <p class="small">Use the left navigation sidebar menu options to browse through deep table registers or quickly run configuration metrics directly via standard workspace tabs.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="projects-list-tab" class="tab-content-section">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-dark text-white py-3" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                    <h5 class="mb-0 fw-bold"><i class="fa-solid fa-folder-open me-2"></i>Project Directory Registry</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Title</th>
                                    <th>Description</th>
                                    <th class="pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($projects as $project)
                                    <tr>
                                        <td class="ps-4 fw-semibold text-dark">{{ $project->title }}</td>
                                        <td class="text-muted">{{ $project->description }}</td>
                                        <td class="pe-4">
                                            @if($project->status == 'Pending')
                                                <span class="badge bg-warning text-dark px-3 py-2" style="border-radius: 20px;">Pending</span>
                                            @elseif($project->status == 'In Progress')
                                                <span class="badge bg-info text-dark px-3 py-2" style="border-radius: 20px;">In Progress</span>
                                            @else
                                                <span class="badge bg-success px-3 py-2" style="border-radius: 20px;">Completed</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">
                                            <i class="fa-regular fa-folder-open fs-2 d-block mb-2 text-secondary"></i>
                                            No projects added yet. Return to the dashboard to register items.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div id="users-tab" class="tab-content-section">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-dark text-white py-3" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                    <h5 class="mb-0 fw-bold"><i class="fa-solid fa-users me-2"></i>Registered Users Directory</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Name</th>
                                    <th>Email Address</th>
                                    <th>Joined Date</th>
                                    <th class="pe-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td class="ps-4 text-secondary fw-bold">#{{ $user->id }}</td>
                                        <td class="fw-semibold text-dark">
                                            @if($user->profile_image)
                                                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="User" class="rounded-circle me-2" style="width: 24px; height: 24px; object-fit: cover;">
                                            @else
                                                <i class="fa-regular fa-circle-user text-primary me-2"></i>
                                            @endif
                                            {{ $user->name }}
                                        </td>
                                        <td class="text-muted">{{ $user->email }}</td>
                                        <td class="text-secondary">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</td>
                                        <td class="pe-4 text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <button class="btn btn-sm btn-outline-primary px-3" style="border-radius: 20px;" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                                    <i class="fa-solid fa-user-pen me-1"></i> Edit
                                                </button>

                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user registry item permanently?');" class="m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger px-3" style="border-radius: 20px;">
                                                        <i class="fa-solid fa-trash-can me-1"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow" style="border-radius: 15px;">
                                                <div class="modal-header bg-primary text-white py-3" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                                                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-user-gear me-2"></i>Edit Account Information</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('users.update', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="modal-body p-4">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-medium text-secondary">Full Name</label>
                                                            <input type="text" class="form-control" name="name" value="{{ $user->name }}" required style="border-radius: 8px;">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-medium text-secondary">Email Address</label>
                                                            <input type="email" class="form-control" name="email" value="{{ $user->email }}" required style="border-radius: 8px;">
                                                        </div>
                                                        <div class="mb-1">
                                                            <label class="form-label fw-medium text-secondary">Change Password <span class="text-muted small fw-normal">(Leave blank to keep current password)</span></label>
                                                            <input type="password" class="form-control" name="password" placeholder="••••••••" style="border-radius: 8px;">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light border-0 p-3" style="border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
                                                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                                                        <button type="submit" class="btn btn-primary px-4 fw-bold" style="border-radius: 8px;">
                                                            <i class="fa-solid fa-floppy-disk me-1"></i> Save Changes
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">No registered accounts found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div id="profile-tab" class="tab-content-section">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                        <div class="card-header bg-dark text-white py-3" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                            <h5 class="mb-0 fw-bold"><i class="fa-solid fa-id-card me-2"></i>Manage My Profile Settings</h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="text-center mb-4">
                                    <div class="profile-avatar-container">
                                        @if(Auth::user()->profile_image)
                                            <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" id="avatarPreview" alt="Profile Image" class="profile-avatar">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=3b82f6&color=fff&size=140" id="avatarPreview" alt="Profile Image" class="profile-avatar">
                                        @endif
                                        
                                        <label for="profileImageInput" class="avatar-upload-btn">
                                            <i class="fa-solid fa-camera text-xs"></i>
                                        </label>
                                        <input type="file" id="profileImageInput" name="profile_image" class="d-none" accept="image/*">
                                    </div>
                                    <p class="text-muted small mt-2 mb-0">Click the camera icon to choose a new profile photo.</p>
                                </div>

                                <hr class="my-4 text-secondary opacity-25">

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium text-secondary">Full Name</label>
                                        <input type="text" class="form-control" name="name" value="{{ Auth::user()->name }}" required style="border-radius: 8px;">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium text-secondary">Email Address</label>
                                        <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}" required style="border-radius: 8px;">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-medium text-secondary">Update Password <span class="text-muted small fw-normal">(Leave blank to keep your current password)</span></label>
                                        <input type="password" class="form-control" name="password" placeholder="••••••••" style="border-radius: 8px;">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4 pt-2">
                                    <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm" style="border-radius: 8px;">
                                        <i class="fa-solid fa-circle-check me-1"></i> Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const links = document.querySelectorAll("#dashboardTabs .nav-link");
            const sections = document.querySelectorAll(".tab-content-section");
            const pageTitle = document.getElementById("page-title");

            links.forEach(link => {
                link.addEventListener("click", function() {
                    const targetId = this.getAttribute("data-target");
                    if (!targetId) return; 

                    links.forEach(l => l.classList.remove("active"));
                    this.classList.add("active");

                    sections.forEach(sec => sec.classList.remove("active-section"));
                    
                    const targetSection = document.getElementById(targetId);
                    if(targetSection) {
                        targetSection.classList.add("active-section");
                    }

                    pageTitle.innerText = this.innerText.trim();
                });
            });

            // Instant Live Image Upload Preview Script Hook
            const fileInput = document.getElementById('profileImageInput');
            const avatarPreview = document.getElementById('avatarPreview');

            if(fileInput) {
                fileInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            avatarPreview.setAttribute('src', e.target.result);
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
</body>
</html>