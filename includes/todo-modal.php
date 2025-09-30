<!-- Add Todo Modal -->
<div id="addTodoModal" class="todo-modal" style="display: none;">
    <div class="todo-modal-content">
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
<div id="updateProgressModal" class="todo-modal" style="display: none;">
    <div class="todo-modal-content">
        <div class="modal-header modal-header-green">
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
<div id="deleteConfirmModal" class="todo-modal" style="display: none;">
    <div class="todo-modal-content todo-modal-small">
        <div class="modal-header modal-header-red">
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
<div id="toggleStatusModal" class="todo-modal" style="display: none;">
    <div class="todo-modal-content todo-modal-small">
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
.todo-modal {
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.65);
    backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.todo-modal-content {
    background-color: #ffffff;
    border-radius: 20px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 25px 70px rgba(33, 147, 176, 0.25), 0 10px 30px rgba(0, 0, 0, 0.15);
    animation: modalSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    overflow: hidden;
    border: 1px solid rgba(109, 213, 237, 0.2);
}

@keyframes modalSlideIn {
    from {
        transform: scale(0.85) translateY(-30px);
        opacity: 0;
    }
    to {
        transform: scale(1) translateY(0);
        opacity: 1;
    }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 26px 32px;
    border-bottom: none;
    background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
    position: relative;
    overflow: hidden;
}

.modal-header::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
    border-radius: 50%;
    transform: translate(50%, -50%);
}

.modal-header h2 {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    color: white;
    letter-spacing: 0.4px;
    position: relative;
    z-index: 1;
}

.close-modal {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    font-weight: normal;
    color: white;
    cursor: pointer;
    line-height: 1;
    transition: all 0.3s ease;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    position: relative;
    z-index: 1;
}

.close-modal:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg) scale(1.1);
}

.modal-body {
    padding: 32px;
    background: #fafbfc;
}

.modal-body .form-group {
    margin-bottom: 26px;
}

.modal-body label {
    display: block;
    margin-bottom: 12px;
    font-weight: 600;
    font-size: 12px;
    color: #2193b0;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.modal-body textarea,
.modal-body input[type="number"] {
    width: 100%;
    padding: 15px 18px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 14px;
    font-family: inherit;
    transition: all 0.3s ease;
    box-sizing: border-box;
    background: white;
    color: #2d3748;
}

.modal-body textarea:focus,
.modal-body input[type="number"]:focus {
    outline: none;
    border-color: #2193b0;
    background: white;
    box-shadow: 0 0 0 4px rgba(33, 147, 176, 0.12);
    transform: translateY(-2px);
}

.modal-body textarea {
    resize: vertical;
    min-height: 100px;
    line-height: 1.6;
}

.modal-footer {
    display: flex;
    gap: 14px;
    justify-content: flex-end;
    margin-top: 32px;
    padding-top: 24px;
    border-top: 2px solid #e8ecf0;
}

.modal-footer button {
    padding: 14px 32px;
    border: none;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    font-size: 12px;
}

.btn-cancel {
    background: #e9ecef;
    color: #495057;
    border: 2px solid transparent;
}

.btn-cancel:hover {
    background: #dee2e6;
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
    border-color: #ced4da;
}

.btn-add {
    background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(33, 147, 176, 0.3);
}

.btn-add:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(33, 147, 176, 0.5);
}

.btn-update {
    background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(33, 147, 176, 0.3);
}

.btn-update:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(33, 147, 176, 0.5);
}

.btn-delete {
    background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(33, 147, 176, 0.3);
}

.btn-delete:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(33, 147, 176, 0.5);
}

.btn-confirm {
    background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(33, 147, 176, 0.3);
}

.btn-confirm:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(33, 147, 176, 0.5);
}

.todo-modal-small {
    max-width: 450px;
}

.confirm-message {
    font-size: 17px;
    color: #2d3748;
    margin-bottom: 16px;
    text-align: center;
    font-weight: 500;
    line-height: 1.6;
    padding: 12px;
}

.confirm-warning {
    font-size: 13px;
    color: #c53030;
    margin-bottom: 24px;
    text-align: center;
    font-weight: 600;
    background: linear-gradient(135deg, rgba(254, 215, 215, 0.4) 0%, rgba(252, 129, 129, 0.15) 100%);
    padding: 14px 18px;
    border-radius: 12px;
    border-left: 4px solid #fc8181;
    border: 2px solid rgba(229, 62, 62, 0.2);
}

/* Progress Slider Styles */
.progress-slider-container {
    margin-top: 16px;
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: linear-gradient(135deg, rgba(33, 147, 176, 0.08) 0%, rgba(109, 213, 237, 0.08) 100%);
    border-radius: 16px;
    border: 1px solid rgba(33, 147, 176, 0.15);
}

.progress-slider {
    flex: 1;
    height: 6px;
    border-radius: 10px;
    outline: none;
    background: #e2e8f0;
    cursor: pointer;
    -webkit-appearance: none;
    appearance: none;
}

.progress-slider::-webkit-slider-track {
    height: 6px;
    border-radius: 10px;
    background: #e2e8f0;
}

.progress-slider::-moz-range-track {
    height: 6px;
    border-radius: 10px;
    background: #e2e8f0;
}

.progress-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
    cursor: pointer;
    box-shadow: 0 2px 10px rgba(33, 147, 176, 0.5);
    transition: all 0.3s ease;
    border: 3px solid white;
}

.progress-slider::-webkit-slider-thumb:hover {
    transform: scale(1.15);
    box-shadow: 0 4px 16px rgba(33, 147, 176, 0.7);
}

.progress-slider::-moz-range-thumb {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
    cursor: pointer;
    border: 3px solid white;
    box-shadow: 0 2px 10px rgba(33, 147, 176, 0.5);
    transition: all 0.3s ease;
}

.progress-slider::-moz-range-thumb:hover {
    transform: scale(1.15);
    box-shadow: 0 4px 16px rgba(33, 147, 176, 0.7);
}

.slider-value {
    font-weight: 700;
    color: white;
    min-width: 55px;
    text-align: center;
    font-size: 15px;
    background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
    padding: 8px 14px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(33, 147, 176, 0.3);
}

/* Modal Header Variants */
.modal-header-green {
    background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
}

.modal-header-red {
    background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
}
</style>
