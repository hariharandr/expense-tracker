import $ from 'jquery';

function showLoading() {
    const overlay = $('<div>').attr('id', 'loading-overlay')
        .css({
            position: 'fixed',
            top: 0,
            left: 0,
            width: '100%',
            height: '100%',
            backgroundColor: 'rgba(0, 0, 0, 0.5)', // Semi-transparent black
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            zIndex: 9999 // Ensure it's on top
        });

    const spinner = $('<div>').addClass('animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-gray-200 dark:border-gray-600'); // Use Tailwind classes

    overlay.append(spinner);
    $('body').append(overlay); // Add overlay to the body
}

function hideLoading() {
    $('#loading-overlay').remove();
}

export { showLoading, hideLoading };