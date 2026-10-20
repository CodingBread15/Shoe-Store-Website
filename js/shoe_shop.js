$(() => {

    // Autofocus
    $('form :input:not(button):first').focus();
    $('.err:first').prev().focus();
    $('.err:first').prev().find(':input:first').focus();
    
    // Confirmation message
    $('[data-confirm]').on('click', e => {
        const text = e.target.dataset.confirm || 'Are you sure?';
        if (!confirm(text)) {
            e.preventDefault();
            e.stopImmediatePropagation();
        }
    });

    //get data
    $('[data-get]').on('click', e => {
        e.preventDefault();
        const url = e.target.dataset.get;
        location = url || location;
    });

    // Initiate POST request
    $('[data-post]').on('click', e => {
        e.preventDefault();
        const url = e.target.dataset.post;
        const f = $('<form>').appendTo(document.body)[0];
        f.method = 'POST';
        f.action = url || location;
        f.submit();
    });

    //reset form
    $('[type=reset]').on('click', e => {
        e.preventDefault();
        location = location;
    });

    // Auto uppercase
    $('[data-upper]').on('input', e => {
        const a = e.target.selectionStart;
        const b = e.target.selectionEnd;
        e.target.value = e.target.value.toUpperCase();
        e.target.setSelectionRange(a, b);
    });

    //photo preview
    $('.upload input[type=file]').on('change', e => {
        const f = e.target.files[0];
        const img = $(e.target).siblings('img')[0];

        if (!img) return;

        img.dataset.src ??= img.src;

        if (f?.type.startsWith('image/')) {
            img.src = URL.createObjectURL(f);
        }
        else {
            img.src = img.dataset.src;
            e.target.value = '';
        }
    });

    //drag and drop
    $('.upload').on('dragover', e => {
        e.preventDefault();
        e.stopPropagation();
        $(e.currentTarget).addClass('dragover');
    });

    $('.upload').on('dragleave', e => {
        e.preventDefault();
        e.stopPropagation();
        $(e.currentTarget).removeClass('dragover');
    });

    $('.upload').on('drop', e => {
        e.preventDefault();
        e.stopPropagation();
        $(e.currentTarget).removeClass('dragover');

        const f = e.originalEvent.dataTransfer.files;

        if(f.length){
            $(e.currentTarget).find('input[type=file]')[0].files = f;
            $(e.currentTarget).find('input[type=file]').trigger('change');
        }
    });

    //photo slider variables here to wait for DOM to load
    slides = $('.slides');
    showSlide(0);

})

    //photo slider
    let slideIndex = 0; //keep tracks of the current slide
    let slides;

    function showSlide(n){
        slides.removeClass('active');

        slideIndex = (n + slides.length) % slides.length
        slides.eq(slideIndex).addClass('active');
    }

    function nextSlide(){
        showSlide(slideIndex + 1);
    }

    function prevSlide(){
        showSlide(slideIndex - 1);
    }