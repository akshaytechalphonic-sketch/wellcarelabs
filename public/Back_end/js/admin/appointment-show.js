// public/assets/js/admin/appointment-show.js

// Main initialization
document.addEventListener('DOMContentLoaded', function() {
    initShareReportButton();
});

// Share Report Button Functionality
function initShareReportButton() {
    const shareButtons = document.querySelectorAll('.share-report-btn');
    
    if (!shareButtons.length) return;
    
    shareButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const shareUrl = this.getAttribute('data-share-url');
            const shareAction = this.getAttribute('data-share-action');
            const shareMessage = this.getAttribute('data-share-message');
            const shareEmail = this.getAttribute('data-share-email');
            const sharePhone = this.getAttribute('data-share-phone');
            const reportId = this.getAttribute('data-report-id');
            
            // Find or create the auto share launcher
            let launcher = document.getElementById('autoShareLauncher');
            if (!launcher) {
                launcher = document.createElement('button');
                launcher.id = 'autoShareLauncher';
                launcher.style.display = 'none';
                document.body.appendChild(launcher);
            }
            
            // Set data attributes
            launcher.setAttribute('data-share-url', shareUrl);
            launcher.setAttribute('data-share-action', shareAction);
            launcher.setAttribute('data-share-message', shareMessage);
            launcher.setAttribute('data-share-email', shareEmail);
            launcher.setAttribute('data-share-phone', sharePhone);
            launcher.setAttribute('data-report-id', reportId);
            
            // Trigger click on launcher
            launcher.click();
        });
    });
    
    // Handle auto share launcher click (if exists)
    const autoLauncher = document.getElementById('autoShareLauncher');
    if (autoLauncher) {
        autoLauncher.addEventListener('click', function() {
            const shareUrl = this.getAttribute('data-share-url');
            const shareAction = this.getAttribute('data-share-action');
            const shareMessage = this.getAttribute('data-share-message');
            const shareEmail = this.getAttribute('data-share-email');
            const sharePhone = this.getAttribute('data-share-phone');
            const reportId = this.getAttribute('data-report-id');
            
            // Check if share modal exists
            const shareModal = document.getElementById('shareReportModal');
            if (shareModal) {
                // Fill modal fields
                const urlInput = shareModal.querySelector('#shareReportUrl');
                const actionInput = shareModal.querySelector('#shareReportAction');
                const messageInput = shareModal.querySelector('#shareReportMessage');
                const emailInput = shareModal.querySelector('#shareReportEmail');
                const phoneInput = shareModal.querySelector('#shareReportPhone');
                const reportIdInput = shareModal.querySelector('#shareReportId');
                
                if (urlInput) urlInput.value = shareUrl || '';
                if (actionInput) actionInput.value = shareAction || '';
                if (messageInput) messageInput.value = shareMessage || '';
                if (emailInput) emailInput.value = shareEmail || '';
                if (phoneInput) phoneInput.value = sharePhone || '';
                if (reportIdInput) reportIdInput.value = reportId || '';
                
                // Show modal using Bootstrap
                if (typeof bootstrap !== 'undefined') {
                    const modal = new bootstrap.Modal(shareModal);
                    modal.show();
                } else {
                    // Fallback if Bootstrap not available
                    shareModal.style.display = 'block';
                    shareModal.classList.add('show');
                }
            } else {
                console.warn('Share modal not found. Make sure @include("partials.share-modal") is present.');
                
                // Fallback: direct share
                if (shareUrl && navigator.share) {
                    navigator.share({
                        title: 'Medical Report',
                        text: shareMessage,
                        url: shareUrl
                    }).catch(err => {
                        console.log('Error sharing:', err);
                        // Fallback to copy link
                        copyToClipboard(shareUrl);
                    });
                } else {
                    // Copy to clipboard fallback
                    copyToClipboard(shareUrl);
                }
            }
        });
    }
}

// Copy to clipboard utility function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Show success message
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                icon: 'success',
                title: 'Link copied to clipboard!'
            });
        } else {
            alert('Link copied to clipboard: ' + text);
        }
    }).catch(err => {
        console.error('Failed to copy:', err);
        // Fallback method
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                icon: 'success',
                title: 'Link copied to clipboard!'
            });
        } else {
            alert('Link copied to clipboard: ' + text);
        }
    });
}

// Smooth scrolling for anchor links (if any)
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
}

// Initialize all functions
initSmoothScroll();