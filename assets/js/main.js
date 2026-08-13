document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('.menu-toggle');
  const nav = document.querySelector('.nav');

  if (toggle && nav) {
    toggle.addEventListener('click', () => {
      nav.classList.toggle('open');
    });

    nav.querySelectorAll('a').forEach((link) => {
      link.addEventListener('click', () => nav.classList.remove('open'));
    });
  }

  const lightbox = document.querySelector('#works-lightbox');
  if (lightbox) {
    const lightboxImg = lightbox.querySelector('img');
    const lightboxCaption = lightbox.querySelector('.lightbox-caption');
    const closeBtn = lightbox.querySelector('.lightbox-close');

    const closeLightbox = () => {
      lightbox.hidden = true;
      if (lightboxImg) {
        lightboxImg.src = '';
      }
    };

    document.querySelectorAll('.works-thumb').forEach((btn) => {
      btn.addEventListener('click', () => {
        if (!lightboxImg) {
          return;
        }
        lightboxImg.src = btn.dataset.full || '';
        lightboxImg.alt = btn.dataset.caption || '';
        if (lightboxCaption) {
          lightboxCaption.textContent = btn.dataset.caption || '';
        }
        lightbox.hidden = false;
      });
    });

    if (closeBtn) {
      closeBtn.addEventListener('click', closeLightbox);
    }

    lightbox.addEventListener('click', (event) => {
      if (event.target === lightbox) {
        closeLightbox();
      }
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && !lightbox.hidden) {
        closeLightbox();
      }
    });
  }

  const quoteForm = document.querySelector('#quote-form');
  if (!quoteForm) {
    return;
  }

  const syncChips = (selectId) => {
    const select = document.getElementById(selectId);
    if (!select) {
      return;
    }
    const value = select.value;
    quoteForm.querySelectorAll(`.choice-chip[data-target="${selectId}"]`).forEach((chip) => {
      chip.classList.toggle('active', chip.dataset.value === value);
    });
  };

  quoteForm.querySelectorAll('.choice-chip').forEach((chip) => {
    chip.addEventListener('click', () => {
      const targetId = chip.dataset.target;
      const value = chip.dataset.value;
      const select = document.getElementById(targetId);
      if (!select) {
        return;
      }
      select.value = value;
      syncChips(targetId);
    });
  });

  ['vehicle_type', 'transport_method'].forEach((id) => {
    const select = document.getElementById(id);
    if (select) {
      select.addEventListener('change', () => syncChips(id));
      syncChips(id);
    }
  });

  const submitHtml = `${quoteForm.dataset.request || 'اطلب عرض السعر'} <span class="submit-arrow" aria-hidden="true">${document.documentElement.dir === 'ltr' ? '→' : '←'}</span>`;

  quoteForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    const submitBtn = quoteForm.querySelector('[type="submit"]');
    const alertBox = document.querySelector('#quote-alert');
    const formData = new FormData(quoteForm);

    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.textContent = quoteForm.dataset.sending || 'جاري الإرسال...';
    }

    try {
      const response = await fetch('api/submit-quote.php', {
        method: 'POST',
        body: formData,
      });
      const data = await response.json();

      if (alertBox) {
        alertBox.hidden = false;
        alertBox.className = 'alert ' + (data.ok ? 'alert-success' : 'alert-error');
        alertBox.textContent = data.message || (data.ok ? 'تم إرسال الطلب بنجاح' : 'حدث خطأ');
      }

      if (data.ok) {
        quoteForm.reset();
        const vehicleSelect = document.getElementById('vehicle_type');
        const methodSelect = document.getElementById('transport_method');
        if (vehicleSelect && vehicleSelect.options.length > 1) {
          vehicleSelect.selectedIndex = 1;
        }
        if (methodSelect && methodSelect.options.length > 1) {
          methodSelect.selectedIndex = 1;
        }
        syncChips('vehicle_type');
        syncChips('transport_method');
      }
    } catch (error) {
      if (alertBox) {
        alertBox.hidden = false;
        alertBox.className = 'alert alert-error';
        alertBox.textContent = quoteForm.dataset.error || 'تعذر الاتصال بالخادم، حاول مرة أخرى.';
      }
    } finally {
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.innerHTML = submitHtml;
      }
    }
  });
});
