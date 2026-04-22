/**
 * CitiLife System - Premium Alert Utilities
 * Provides standardized SweetAlert2 wrappers for the system.
 */

const alerts = {
  /**
   * Show a smooth toast notification
   * @param {string} title - Message to show
   * @param {string} icon - 'success', 'error', 'warning', 'info'
   */
  toast: function(title, icon = 'success') {
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    });

    Toast.fire({
      icon: icon,
      title: title
    });
  },

  /**
   * Show a professional success modal
   */
  success: function(title, text = '') {
    return Swal.fire({
      icon: 'success',
      title: title,
      text: text,
      confirmButtonColor: '#10b981', // green-500
      customClass: {
        popup: 'rounded-3xl border-0 shadow-2xl',
        confirmButton: 'rounded-xl px-8 py-3 font-bold'
      }
    });
  },

  /**
   * Show a professional error modal
   */
  error: function(title, text = 'Something went wrong.') {
    return Swal.fire({
      icon: 'error',
      title: title,
      text: text,
      confirmButtonColor: '#ef4444', // red-500
      customClass: {
        popup: 'rounded-3xl border-0 shadow-2xl',
        confirmButton: 'rounded-xl px-8 py-3 font-bold'
      }
    });
  },

  /**
   * Show a professional confirmation dialog
   */
  confirm: function(title, text, confirmText = 'Yes, Proceed') {
    return Swal.fire({
      title: title,
      text: text,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3b82f6', // blue-500
      cancelButtonColor: '#6b7280', // gray-500
      confirmButtonText: confirmText,
      customClass: {
        popup: 'rounded-3xl border-0 shadow-2xl',
        confirmButton: 'rounded-xl px-8 py-3 font-bold',
        cancelButton: 'rounded-xl px-8 py-3 font-bold'
      }
    });
  }
};

// Global exports
window.toast = alerts.toast;
window.successAlert = alerts.success;
window.errorAlert = alerts.error;
window.confirmAlert = alerts.confirm;
