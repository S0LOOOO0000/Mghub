// Todo Management System
let currentFilter = 'all';
let allTodos = [];
let todosLoaded = false;

// Load todos on page load
document.addEventListener('DOMContentLoaded', function() {
    // Small delay to ensure DOM is fully ready
    setTimeout(() => {
        loadTodos();
    }, 100);
    
    // Add todo button event
    const addTodoBtn = document.querySelector('.add-todo-btn');
    if (addTodoBtn) {
        addTodoBtn.addEventListener('click', openAddTodoModal);
    }
    
    // Plus icon in header
    const plusIcon = document.querySelector('.todo .head .icon');
    if (plusIcon) {
        plusIcon.addEventListener('click', openAddTodoModal);
    }
    
    // Add todo form submit
    const addTodoForm = document.getElementById('addTodoForm');
    if (addTodoForm) {
        addTodoForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleAddTodo();
        });
    }
    
    // Update progress form submit
    const updateProgressForm = document.getElementById('updateProgressForm');
    if (updateProgressForm) {
        updateProgressForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleUpdateProgress();
        });
    }
    
    // Progress slider sync
    const progressSlider = document.getElementById('progressSlider');
    const updateProgressValue = document.getElementById('updateProgressValue');
    const sliderValue = document.querySelector('.slider-value');
    
    if (progressSlider && updateProgressValue && sliderValue) {
        progressSlider.addEventListener('input', function() {
            updateProgressValue.value = this.value;
            sliderValue.textContent = this.value + '%';
            updateSliderBackground(this);
        });
        
        updateProgressValue.addEventListener('input', function() {
            progressSlider.value = this.value;
            sliderValue.textContent = this.value + '%';
            updateSliderBackground(progressSlider);
        });
    }
    
    // Close modals when clicking outside
    const modals = document.querySelectorAll('.todo-modal');
    modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeAllModals();
            }
        });
    });
});

// Update slider background based on value
function updateSliderBackground(slider) {
    const value = (slider.value / slider.max) * 100;
    // Create gradient that fills from left to right based on progress
    if (value === 0) {
        slider.style.background = '#e2e8f0';
    } else {
        slider.style.background = `linear-gradient(to right, #2193b0 0%, #6dd5ed ${value}%, #e2e8f0 ${value}%, #e2e8f0 100%)`;
    }
}

// Load todos from database
function loadTodos() {
    // Show loading state
    const todoList = document.querySelector('.todo-list');
    if (todoList) {
        todoList.innerHTML = '<li style="text-align: center; padding: 20px; color: #999;">Loading todos...</li>';
    }
    
    // Check if station is defined
    const station = typeof DASHBOARD_STATION !== 'undefined' ? DASHBOARD_STATION : null;
    const userId = typeof DASHBOARD_USER_ID !== 'undefined' ? DASHBOARD_USER_ID : null;
    
    if (!station) {
        if (todoList) {
            todoList.innerHTML = '<li style="text-align: center; padding: 20px; color: #e74c3c;">Error: Dashboard station not configured</li>';
        }
        return;
    }
    
    fetch('../php/get-todo.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            station: station,
            user_id: userId
        })
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                allTodos = data.todos || [];
                todosLoaded = true;
                renderTodos();
            } else {
                // Show error in UI
                const todoList = document.querySelector('.todo-list');
                if (todoList) {
                    todoList.innerHTML = `<li style="text-align: center; padding: 20px; color: #e74c3c;">Error: ${data.message}</li>`;
                }
            }
        })
        .catch(error => {
            // Show error in UI
            const todoList = document.querySelector('.todo-list');
            if (todoList) {
                todoList.innerHTML = '<li style="text-align: center; padding: 20px; color: #e74c3c;">Failed to load todos. Please refresh.</li>';
            }
        });
}

// Render todos to the UI
function renderTodos() {
    const todoList = document.querySelector('.todo-list');
    if (!todoList) {
        return;
    }
    
    // Prevent rendering if todos haven't loaded yet
    if (!todosLoaded && allTodos.length === 0) {
        return;
    }
    
    // Filter todos based on current filter
    let filteredTodos = allTodos;
    if (currentFilter === 'completed') {
        filteredTodos = allTodos.filter(todo => todo.is_completed == 1);
    } else if (currentFilter === 'pending') {
        filteredTodos = allTodos.filter(todo => todo.is_completed == 0);
    }
    
    // Clear current list
    todoList.innerHTML = '';
    
    // If no todos, show message
    if (filteredTodos.length === 0) {
        const message = currentFilter === 'all' ? 'No todos found' : `No ${currentFilter} todos`;
        todoList.innerHTML = `<li style="text-align: center; padding: 20px; color: #999;">${message}</li>`;
        return;
    }
    
    // Render each todo
    filteredTodos.forEach(todo => {
        const li = createTodoElement(todo);
        todoList.appendChild(li);
    });
}

// Create a todo list item element
function createTodoElement(todo) {
    const li = document.createElement('li');
    li.className = todo.is_completed == 1 ? 'completed' : 'not-completed';
    li.setAttribute('data-progress', todo.progress);
    li.setAttribute('data-todo-id', todo.todo_id);
    // Set CSS variable for progress bar width
    li.style.setProperty('--progress-width', todo.progress + '%');
    
    // Todo text
    const p = document.createElement('p');
    p.textContent = todo.todo_text;
    if (todo.is_completed == 1) {
        p.style.textDecoration = 'line-through';
        p.style.opacity = '0.6';
    }
    
    // Actions container
    const actionsDiv = document.createElement('div');
    actionsDiv.className = 'todo-actions';
    
    // Progress text
    const progressSpan = document.createElement('span');
    progressSpan.className = 'progress-text';
    progressSpan.textContent = todo.progress + '%';
    
    // Menu container
    const menuContainer = document.createElement('div');
    menuContainer.className = 'menu-icon';
    menuContainer.style.position = 'relative';
    
    // Menu icon
    const menuIcon = document.createElement('i');
    menuIcon.className = 'bx bx-dots-vertical-rounded';
    menuIcon.style.cursor = 'pointer';
    
    // Menu content
    const menuContent = document.createElement('dl');
    menuContent.className = 'content-menu';
    menuContent.style.display = 'none';
    
    // Edit option (update progress)
    const editItem = document.createElement('dt');
    editItem.className = 'menu-item';
    const editLink = document.createElement('a');
    editLink.href = '#';
    editLink.textContent = 'Update Progress';
    editLink.onclick = (e) => {
        e.preventDefault();
        e.stopPropagation();
        updateTodoProgress(todo.todo_id, todo.progress);
        menuContent.style.display = 'none';
    };
    editItem.appendChild(editLink);
    
    // Delete option
    const deleteItem = document.createElement('dt');
    deleteItem.className = 'menu-item';
    const deleteLink = document.createElement('a');
    deleteLink.href = '#';
    deleteLink.textContent = 'Delete';
    deleteLink.onclick = (e) => {
        e.preventDefault();
        e.stopPropagation();
        deleteTodo(todo.todo_id);
        menuContent.style.display = 'none';
    };
    deleteItem.appendChild(deleteLink);
    
    // Mark as Completed/Pending option
    const toggleItem = document.createElement('dt');
    toggleItem.className = 'menu-item';
    const toggleLink = document.createElement('a');
    toggleLink.href = '#';
    toggleLink.textContent = todo.is_completed == 1 ? 'Mark as Pending' : 'Mark as Completed';
    toggleLink.onclick = (e) => {
        e.preventDefault();
        e.stopPropagation();
        toggleTodoStatus(todo.todo_id, todo.is_completed);
        menuContent.style.display = 'none';
    };
    toggleItem.appendChild(toggleLink);
    
    // Append menu items
    menuContent.appendChild(editItem);
    menuContent.appendChild(deleteItem);
    menuContent.appendChild(toggleItem);
    
    // Toggle menu on click
    menuContainer.addEventListener('click', (e) => {
        e.stopPropagation();
        e.preventDefault();
        
        // Close all other menus
        document.querySelectorAll('.content-menu').forEach(menu => {
            if (menu !== menuContent) {
                menu.style.display = 'none';
            }
        });
        
        // Toggle this menu
        if (menuContent.style.display === 'none' || menuContent.style.display === '') {
            menuContent.style.display = 'block';
        } else {
            menuContent.style.display = 'none';
        }
    });
    
    menuContainer.appendChild(menuIcon);
    menuContainer.appendChild(menuContent);
    
    // Assemble the todo item
    actionsDiv.appendChild(progressSpan);
    actionsDiv.appendChild(menuContainer);
    
    li.appendChild(p);
    li.appendChild(actionsDiv);
    
    return li;
}

// Open add todo modal
function openAddTodoModal() {
    const modal = document.getElementById('addTodoModal');
    if (modal) {
        modal.style.display = 'flex';
        // Focus on textarea
        setTimeout(() => {
            const textarea = document.getElementById('todoText');
            if (textarea) textarea.focus();
        }, 100);
    }
}

// Close add todo modal
function closeAddTodoModal() {
    const modal = document.getElementById('addTodoModal');
    if (modal) {
        modal.style.display = 'none';
        // Reset form
        const form = document.getElementById('addTodoForm');
        if (form) form.reset();
    }
}

// Handle add todo form submission
function handleAddTodo() {
    const todoText = document.getElementById('todoText').value.trim();
    const todoProgress = parseInt(document.getElementById('todoProgress').value) || 0;
    
    if (!todoText) {
        alert('Please enter a todo description');
        return;
    }
    
    addTodo(todoText, todoProgress);
}

// Add a new todo
function addTodo(todoText, progress = 0) {
    const station = typeof DASHBOARD_STATION !== 'undefined' ? DASHBOARD_STATION : null;
    const userId = typeof DASHBOARD_USER_ID !== 'undefined' ? DASHBOARD_USER_ID : null;
    
    if (!station) {
        alert('Error: Dashboard station not configured');
        return;
    }
    
    fetch('../php/add-todo.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            todo_text: todoText,
            progress: progress,
            station: station,
            user_id: userId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            closeAddTodoModal();
            // Reload todos
            loadTodos();
        } else {
            alert('Failed to add todo: ' + data.message);
        }
    })
    .catch(error => {
        alert('An error occurred while adding the todo');
    });
}

// Open update progress modal
function updateTodoProgress(todoId, currentProgress) {
    const modal = document.getElementById('updateProgressModal');
    const todoIdInput = document.getElementById('updateTodoId');
    const progressInput = document.getElementById('updateProgressValue');
    const progressSlider = document.getElementById('progressSlider');
    const sliderValue = document.querySelector('.slider-value');
    
    if (modal && todoIdInput && progressInput && progressSlider) {
        todoIdInput.value = todoId;
        progressInput.value = currentProgress;
        progressSlider.value = currentProgress;
        sliderValue.textContent = currentProgress + '%';
        updateSliderBackground(progressSlider);
        modal.style.display = 'flex';
        
        // Focus on input
        setTimeout(() => progressInput.focus(), 100);
    }
}

// Close update progress modal
function closeUpdateProgressModal() {
    const modal = document.getElementById('updateProgressModal');
    if (modal) {
        modal.style.display = 'none';
        const form = document.getElementById('updateProgressForm');
        if (form) form.reset();
    }
}

// Handle update progress form submission
function handleUpdateProgress() {
    const todoId = parseInt(document.getElementById('updateTodoId').value);
    const progress = parseInt(document.getElementById('updateProgressValue').value);
    
    if (isNaN(progress) || progress < 0 || progress > 100) {
        alert('Please enter a valid number between 0 and 100');
        return;
    }
    
    fetch('../php/update-todo.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            todo_id: todoId,
            progress: progress
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeUpdateProgressModal();
            loadTodos();
        } else {
            alert('Failed to update todo: ' + data.message);
        }
    })
    .catch(error => {
        alert('An error occurred while updating the todo');
    });
}

// Open toggle status modal
function toggleTodoStatus(todoId, currentStatus) {
    const modal = document.getElementById('toggleStatusModal');
    const todoIdInput = document.getElementById('toggleTodoId');
    const statusInput = document.getElementById('toggleCurrentStatus');
    const message = document.getElementById('toggleStatusMessage');
    
    if (modal && todoIdInput && statusInput && message) {
        todoIdInput.value = todoId;
        statusInput.value = currentStatus;
        
        if (currentStatus == 1) {
            message.textContent = 'Mark this todo as pending?';
        } else {
            message.textContent = 'Mark this todo as completed?';
        }
        
        modal.style.display = 'flex';
    }
}

// Close toggle status modal
function closeToggleStatusModal() {
    const modal = document.getElementById('toggleStatusModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Confirm toggle status
function confirmToggleStatus() {
    const todoId = parseInt(document.getElementById('toggleTodoId').value);
    const currentStatus = parseInt(document.getElementById('toggleCurrentStatus').value);
    const newStatus = currentStatus == 1 ? 0 : 1;
    
    fetch('../php/update-todo.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            todo_id: todoId,
            is_completed: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeToggleStatusModal();
            loadTodos();
        } else {
            alert('Failed to update todo: ' + data.message);
        }
    })
    .catch(error => {
        alert('An error occurred while updating the todo');
    });
}

// Open delete confirmation modal
function deleteTodo(todoId) {
    const modal = document.getElementById('deleteConfirmModal');
    const todoIdInput = document.getElementById('deleteTodoId');
    
    if (modal && todoIdInput) {
        todoIdInput.value = todoId;
        modal.style.display = 'flex';
    }
}

// Close delete confirmation modal
function closeDeleteConfirmModal() {
    const modal = document.getElementById('deleteConfirmModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Confirm delete todo
function confirmDeleteTodo() {
    const todoId = parseInt(document.getElementById('deleteTodoId').value);
    
    fetch('../php/delete-todo.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            todo_id: todoId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeDeleteConfirmModal();
            loadTodos();
        } else {
            alert('Failed to delete todo: ' + data.message);
        }
    })
    .catch(error => {
        alert('An error occurred while deleting the todo');
    });
}

// Filter todos
function filterTodos(filter) {
    currentFilter = filter;
    
    // Update active button
    document.querySelectorAll('.todo-filters button').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Find and set the active button based on filter
    const buttons = document.querySelectorAll('.todo-filters button');
    buttons.forEach(btn => {
        const btnText = btn.textContent.toLowerCase();
        if ((filter === 'all' && btnText === 'all') ||
            (filter === 'completed' && btnText === 'completed') ||
            (filter === 'pending' && btnText === 'pending')) {
            btn.classList.add('active');
        }
    });
    
    // Re-render with the new filter
    renderTodos();
}

// Close menus when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.menu-icon')) {
        document.querySelectorAll('.content-menu').forEach(menu => {
            menu.style.display = 'none';
        });
    }
});

// Close all modals helper function
function closeAllModals() {
    closeAddTodoModal();
    closeUpdateProgressModal();
    closeDeleteConfirmModal();
    closeToggleStatusModal();
}
