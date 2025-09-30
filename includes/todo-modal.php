<!-- Add Todo Modal -->
<div id="addTodoModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add New Todo</h2>
            <span class="close-modal" onclick="closeAddTodoModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form id="addTodoForm">
                <div class="form-group">
                    <label for="todoText">Todo Description</label>
                    <textarea id="todoText" name="todoText" rows="3" placeholder="Enter your todo..." required></textarea>
                </div>
                <div class="form-group">
                    <label for="todoProgress">Initial Progress (%)</label>
                    <input type="number" id="todoProgress" name="todoProgress" min="0" max="100" value="0" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeAddTodoModal()">Cancel</button>
                    <button type="submit" class="btn-add">Add Todo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Progress Modal -->
<div id="updateProgressModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Update Progress</h2>
            <span class="close-modal" onclick="closeUpdateProgressModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form id="updateProgressForm">
                <input type="hidden" id="updateTodoId" />
                <div class="form-group">
                    <label for="updateProgressValue">Progress (%)</label>
                    <input type="number" id="updateProgressValue" name="updateProgressValue" min="0" max="100" required />
                    <div class="progress-slider-container">
                        <input type="range" id="progressSlider" min="0" max="100" value="0" class="progress-slider" />
                        <span class="slider-value">0%</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeUpdateProgressModal()">Cancel</button>
                    <button type="submit" class="btn-update">Update Progress</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="modal" style="display: none;">
    <div class="modal-content modal-small">
        <div class="modal-header">
            <h2>Delete Todo</h2>
            <span class="close-modal" onclick="closeDeleteConfirmModal()">&times;</span>
        </div>
        <div class="modal-body">
            <input type="hidden" id="deleteTodoId" />
            <p class="confirm-message">Are you sure you want to delete this todo?</p>
            <p class="confirm-warning">This action cannot be undone.</p>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeDeleteConfirmModal()">Cancel</button>
                <button type="button" class="btn-delete" onclick="confirmDeleteTodo()">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Toggle Status Confirmation Modal -->
<div id="toggleStatusModal" class="modal" style="display: none;">
    <div class="modal-content modal-small">
        <div class="modal-header">
            <h2>Change Todo Status</h2>
            <span class="close-modal" onclick="closeToggleStatusModal()">&times;</span>
        </div>
        <div class="modal-body">
            <input type="hidden" id="toggleTodoId" />
            <input type="hidden" id="toggleCurrentStatus" />
            <p class="confirm-message" id="toggleStatusMessage">Mark this todo as completed?</p>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeToggleStatusModal()">Cancel</button>
                <button type="button" class="btn-confirm" onclick="confirmToggleStatus()">Confirm</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Todo Modal Styles */
.modal {
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background-color: var(--light);
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    animation: modalSlideIn 0.3s ease;
}

@keyframes modalSlideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 2px solid var(--grey);
}

.modal-header h2 {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
    color: var(--dark);
}

.close-modal {
    font-size: 32px;
    font-weight: bold;
    color: var(--dark);
    cursor: pointer;
    line-height: 1;
    transition: color 0.2s;
}

.close-modal:hover {
    color: var(--red);
}

.modal-body {
    padding: 24px;
}

.modal-body .form-group {
    margin-bottom: 20px;
}

.modal-body label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    font-size: 14px;
    color: var(--dark);
}

.modal-body textarea,
.modal-body input[type="number"] {
    width: 100%;
    padding: 12px;
    border: 1px solid var(--grey);
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
    transition: border-color 0.2s;
    box-sizing: border-box;
}

.modal-body textarea:focus,
.modal-body input[type="number"]:focus {
    outline: none;
    border-color: var(--blue);
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

.modal-body textarea {
    resize: vertical;
    min-height: 80px;
}

.modal-footer {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 24px;
}

.modal-footer button {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-cancel {
    background: var(--grey);
    color: var(--dark);
}

.btn-cancel:hover {
    background: #d0d0d0;
}

.btn-add {
    background: var(--blue);
    color: white;
}

.btn-add:hover {
    background: #2980b9;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
}

.btn-update {
    background: var(--green);
    color: white;
}

.btn-update:hover {
    background: #27ae60;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(46, 204, 113, 0.3);
}

.btn-delete {
    background: var(--red);
    color: white;
}

.btn-delete:hover {
    background: #c0392b;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
}

.btn-confirm {
    background: var(--blue);
    color: white;
}

.btn-confirm:hover {
    background: #2980b9;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
}

.modal-small {
    max-width: 400px;
}

.confirm-message {
    font-size: 15px;
    color: var(--dark);
    margin-bottom: 8px;
    text-align: center;
}

.confirm-warning {
    font-size: 13px;
    color: var(--red);
    margin-bottom: 20px;
    text-align: center;
    font-weight: 500;
}

/* Progress Slider Styles */
.progress-slider-container {
    margin-top: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.progress-slider {
    flex: 1;
    height: 6px;
    border-radius: 3px;
    outline: none;
    background: linear-gradient(to right, var(--blue) 0%, var(--blue) 0%, #ddd 0%, #ddd 100%);
}

.progress-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: var(--blue);
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.progress-slider::-moz-range-thumb {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: var(--blue);
    cursor: pointer;
    border: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.slider-value {
    font-weight: 600;
    color: var(--blue);
    min-width: 45px;
    text-align: right;
    font-size: 14px;
}
</style>
