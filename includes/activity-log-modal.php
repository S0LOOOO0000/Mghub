<!-- Activity Log Details Modal -->
<div id="activityLogModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Activity Log Details</h3>
            <span class="close" id="closeActivityModal">&times;</span>
        </div>
        <div class="modal-body">
            <div class="log-details-container">
                <div class="log-info-grid">
                    <div class="log-info-item">
                        <label>Log ID:</label>
                        <span id="modalLogId">-</span>
                    </div>
                    <div class="log-info-item">
                        <label>Role:</label>
                        <span id="modalRole">-</span>
                    </div>
                    <div class="log-info-item">
                        <label>Action:</label>
                        <span id="modalAction">-</span>
                    </div>
                    <div class="log-info-item">
                        <label>Date:</label>
                        <span id="modalDate">-</span>
                    </div>
                    <div class="log-info-item">
                        <label>Time:</label>
                        <span id="modalTime">-</span>
                    </div>
                </div>
                <div class="log-details-section">
                    <label>Full Details:</label>
                    <div class="details-content" id="modalDetails">
                        <!-- Details will be populated here -->
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="closeActivityModalBtn">Close</button>
        </div>
    </div>
</div>

<style>
/* Activity Log Modal Styles - Matching System Design */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    animation: fadeIn 0.3s ease;
}

.modal-content {
    background-color: var(--light);
    margin: 5% auto;
    padding: 0;
    border-radius: 20px;
    width: 85%;
    max-width: 900px;
    max-height: 85vh;
    overflow-y: auto;
    box-shadow: 0 4px 20px var(--opacity-50);
    animation: slideIn 0.3s ease;
    border: 1px solid var(--grey);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px;
    border-bottom: 2px solid var(--grey);
    background: linear-gradient(135deg, var(--light) 0%, #f5f5f5 100%);
    border-radius: 20px 20px 0 0;
}

.modal-header h3 {
    margin: 0;
    color: var(--font);
    font-size: 1.5rem;
    font-weight: 600;
    font-family: var(--poppins);
}

.close {
    color: var(--font);
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background-color: var(--grey);
}

.close:hover,
.close:focus {
    color: var(--light);
    background-color: var(--active);
    transform: scale(1.1);
}

.modal-body {
    padding: 24px;
    background-color: var(--light);
}

.log-details-container {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.log-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    padding: 20px;
    background-color: var(--light);
    border-radius: 12px;
    border: 2px solid var(--grey);
    box-shadow: 0 2px 8px var(--opacity-30);
}

.log-info-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding: 12px;
    background-color: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid var(--blue);
}

.log-info-item label {
    font-weight: 600;
    color: var(--font);
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    font-family: var(--poppins);
}

.log-info-item span {
    color: var(--font);
    font-size: 1rem;
    padding: 4px 0;
    font-family: var(--lato);
    font-weight: 500;
}

.log-details-section {
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding: 20px;
    background-color: var(--light);
    border-radius: 12px;
    border: 2px solid var(--grey);
    box-shadow: 0 2px 8px var(--opacity-30);
}

.log-details-section label {
    font-weight: 600;
    color: var(--font);
    font-size: 1.1rem;
    margin-bottom: 8px;
    font-family: var(--poppins);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.details-content {
    background-color: #f8f9fa;
    border: 2px solid var(--grey);
    border-radius: 8px;
    padding: 16px;
    font-family: var(--lato);
    font-size: 0.9rem;
    line-height: 1.6;
    white-space: pre-wrap;
    word-wrap: break-word;
    max-height: 300px;
    overflow-y: auto;
    color: var(--font);
    border-left: 4px solid var(--blue);
}

.modal-footer {
    padding: 20px 24px;
    border-top: 2px solid var(--grey);
    background: linear-gradient(135deg, var(--light) 0%, #f5f5f5 100%);
    border-radius: 0 0 20px 20px;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: 600;
    transition: all 0.3s ease;
    font-family: var(--poppins);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-secondary {
    background-color: var(--active);
    color: var(--light);
    box-shadow: 0 2px 8px var(--opacity-30);
}

.btn-secondary:hover {
    background-color: var(--font);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px var(--opacity-50);
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from { 
        opacity: 0;
        transform: translateY(-50px) scale(0.95);
    }
    to { 
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .modal-content {
        width: 95%;
        margin: 10% auto;
        border-radius: 16px;
    }
    
    .log-info-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .modal-header h3 {
        font-size: 1.2rem;
    }
    
    .modal-header {
        padding: 20px;
    }
    
    .modal-body {
        padding: 20px;
    }
    
    .log-info-item {
        padding: 10px;
    }
}

/* Custom scrollbar for details content */
.details-content::-webkit-scrollbar {
    width: 6px;
}

.details-content::-webkit-scrollbar-track {
    background: var(--grey);
    border-radius: 3px;
}

.details-content::-webkit-scrollbar-thumb {
    background: var(--active);
    border-radius: 3px;
}

.details-content::-webkit-scrollbar-thumb:hover {
    background: var(--font);
}
</style>
