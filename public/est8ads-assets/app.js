(() => {
  const form = document.getElementById('propertyMoveForm');
  const toast = document.getElementById('toast');
  const modal = document.getElementById('successModal');
  const saveDraftBtn = document.getElementById('saveDraft');
  const clearDraftBtn = document.getElementById('clearDraft');
  const menuButton = document.querySelector('.menu-button');
  const mobileMenu = document.querySelector('.mobile-menu');
  const photoInput = document.getElementById('sellPhotos');
  const photoCount = document.getElementById('photoCount');
  const uploadField = document.querySelector('.upload-field');
  const moveType = form.elements.move_type;
  const sellSection = document.getElementById('form-sell');
  const buySection = document.getElementById('form-buy');
  const sellProgress = document.querySelector('.progress-item[href="#form-sell"]');
  const buyProgress = document.querySelector('.progress-item[href="#form-buy"]');
  const draftKey = 'est8adsPropertyMoveDraftV3';

  const summary = {
    sell: document.getElementById('summarySell'),
    sellLocation: document.getElementById('summarySellLocation'),
    buy: document.getElementById('summaryBuy'),
    buyLocation: document.getElementById('summaryBuyLocation'),
    salePrice: document.getElementById('summarySalePrice'),
    buyPrice: document.getElementById('summaryBuyPrice'),
    accessLabel: document.getElementById('summaryAccessLabel'),
    accessTitle: document.getElementById('summaryAccessTitle'),
    accessText: document.getElementById('summaryAccessText'),
    accessLink: document.getElementById('summaryAccessLink')
  };

  const showToast = (message) => {
    toast.textContent = message;
    toast.classList.add('show');
    window.clearTimeout(showToast.timer);
    showToast.timer = window.setTimeout(() => toast.classList.remove('show'), 2600);
  };

  const getValue = (name) => {
    const element = form.elements[name];
    return element ? String(element.value || '').trim() : '';
  };

  const formatMoney = (amount, currency) => {
    if (!amount) return '—';
    const value = Number(amount);
    if (!Number.isFinite(value)) return '—';
    return new Intl.NumberFormat('en', { style: 'currency', currency: currency || 'EUR', maximumFractionDigits: 0 }).format(value);
  };

  const setSectionActive = (section, progress, active) => {
    section.classList.toggle('is-hidden', !active);
    progress.classList.toggle('is-hidden', !active);
    section.querySelectorAll('input, select, textarea').forEach((field) => {
      field.disabled = !active;
    });
  };

  const applyMoveMode = () => {
    const mode = getValue('move_type');
    const sellActive = mode !== 'Only buy a property';
    const buyActive = mode !== 'Only sell a property';
    setSectionActive(sellSection, sellProgress, sellActive);
    setSectionActive(buySection, buyProgress, buyActive);
  };

  const updateSummary = () => {
    summary.sell.textContent = getValue('sell_title') || getValue('sell_type') || 'Your current property';
    summary.sellLocation.textContent = [getValue('sell_city'), getValue('sell_country')].filter(Boolean).join(', ') || 'Location not added';
    summary.buy.textContent = getValue('buy_type') || 'Your next property';
    summary.buyLocation.textContent = [getValue('buy_city'), getValue('buy_country')].filter(Boolean).join(', ') || 'Location not added';
    summary.salePrice.textContent = formatMoney(getValue('sell_price'), getValue('sell_currency'));
    summary.buyPrice.textContent = formatMoney(getValue('buy_budget_max'), getValue('buy_currency'));

    const userType = getValue('user_type');
    const isAgency = userType === 'Real estate agency or agent';
    if (isAgency) {
      summary.accessLabel.textContent = 'VILLA BIT AI AGENCY ACCESS';
      summary.accessTitle.textContent = 'Unlimited free publishing';
      summary.accessText.textContent = 'Agencies using Villa Bit AI can publish unlimited EST8ADS requests through the fully integrated Villa Bit AI Server.';
      summary.accessLink.hidden = false;
    } else {
      summary.accessLabel.textContent = 'PRIVATE / ONE-TIME USER';
      summary.accessTitle.textContent = '$12 for 30 days';
      summary.accessText.textContent = 'Includes one property move request and EST8ADS AI Server analysis. No sales commission.';
      summary.accessLink.hidden = true;
    }
  };

  const serializeForm = () => {
    const data = {};
    new FormData(form).forEach((value, key) => {
      if (value instanceof File) return;
      if (data[key]) data[key] = [].concat(data[key], value);
      else data[key] = value;
    });
    [...form.querySelectorAll('input[type="checkbox"]')].forEach((el) => { data[el.name] = el.checked; });
    return data;
  };

  const saveDraft = () => {
    localStorage.setItem(draftKey, JSON.stringify(serializeForm()));
    showToast('Your EST8ADS property move draft has been saved.');
  };

  const restoreDraft = () => {
    const raw = localStorage.getItem(draftKey);
    if (!raw) return;
    try {
      const data = JSON.parse(raw);
      Object.entries(data).forEach(([name, value]) => {
        const field = form.elements[name];
        if (!field) return;
        if (field instanceof RadioNodeList) {
          [...field].forEach((radio) => { radio.checked = radio.value === value; });
        } else if (field.type === 'checkbox') {
          field.checked = Boolean(value);
        } else {
          field.value = Array.isArray(value) ? value[0] : value;
        }
      });
      updateSummary();
      showToast('Saved draft restored.');
    } catch (_) {
      localStorage.removeItem(draftKey);
    }
  };

  const clearDraft = () => {
    if (!window.confirm('Clear all fields and remove the saved draft?')) return;
    form.reset();
    if (window.jQuery?.fn?.select2) window.jQuery('.property-type-select').val(null).trigger('change');
    localStorage.removeItem(draftKey);
    photoCount.textContent = 'No files selected';
    form.querySelectorAll('.invalid').forEach((el) => el.classList.remove('invalid'));
    applyMoveMode();
    updateSummary();
    showToast('All fields have been cleared.');
  };

  form.addEventListener('input', (event) => {
    if (event.target.classList.contains('invalid') && (event.target.value || event.target.checked)) event.target.classList.remove('invalid');
    updateSummary();
  });
  form.addEventListener('change', (event) => {
    if (event.target.name === 'user_type') {
      if (event.target.value === 'Buyer and seller') moveType.value = 'Both transactions at the same time';
      if (event.target.value === 'Private buyer or seller' && moveType.value === 'Both transactions at the same time') moveType.value = '';
    }
    applyMoveMode();
    updateSummary();
  });

  form.addEventListener('submit', (event) => {
    const invalid = [...form.querySelectorAll('[required]')].filter((el) => !el.disabled).filter((el) => el.type === 'checkbox' ? !el.checked : !String(el.value).trim());
    form.querySelectorAll('.invalid').forEach((el) => el.classList.remove('invalid'));
    invalid.forEach((el) => el.classList.add('invalid'));
    if (invalid.length) {
      event.preventDefault();
      invalid[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
      invalid[0].focus({ preventScroll: true });
      showToast(`Please complete ${invalid.length} required field${invalid.length === 1 ? '' : 's'}.`);
    }
  });

  if (saveDraftBtn) saveDraftBtn.addEventListener('click', saveDraft);
  if (clearDraftBtn) clearDraftBtn.addEventListener('click', clearDraft);

  photoInput.addEventListener('change', () => {
    const count = photoInput.files.length;
    photoCount.textContent = count ? `${count} photo${count === 1 ? '' : 's'} selected` : 'No files selected';
  });
  uploadField.querySelector('.upload-box').addEventListener('click', () => photoInput.click());
  ['dragenter', 'dragover'].forEach((eventName) => uploadField.addEventListener(eventName, (event) => { event.preventDefault(); uploadField.classList.add('dragover'); }));
  ['dragleave', 'drop'].forEach((eventName) => uploadField.addEventListener(eventName, (event) => { event.preventDefault(); uploadField.classList.remove('dragover'); }));
  uploadField.addEventListener('drop', (event) => {
    if (event.dataTransfer.files.length) {
      photoInput.files = event.dataTransfer.files;
      photoInput.dispatchEvent(new Event('change'));
    }
  });

  menuButton.addEventListener('click', () => {
    const open = menuButton.getAttribute('aria-expanded') === 'true';
    menuButton.setAttribute('aria-expanded', String(!open));
    mobileMenu.hidden = open;
  });
  mobileMenu.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => {
    mobileMenu.hidden = true;
    menuButton.setAttribute('aria-expanded', 'false');
  }));

  document.querySelectorAll('.modal-close, .modal-close-button').forEach((button) => button.addEventListener('click', () => {
    modal.hidden = true;
    document.body.style.overflow = '';
  }));
  modal.addEventListener('click', (event) => {
    if (event.target === modal) {
      modal.hidden = true;
      document.body.style.overflow = '';
    }
  });

  const sections = [...document.querySelectorAll('[data-form-section]')];
  const progressItems = [...document.querySelectorAll('.progress-item')];
  const observer = new IntersectionObserver((entries) => {
    const visible = entries.filter((entry) => entry.isIntersecting).sort((a, b) => b.intersectionRatio - a.intersectionRatio)[0];
    if (!visible) return;
    const index = Number(visible.target.dataset.formSection) - 1;
    progressItems.forEach((item, i) => item.classList.toggle('active', i === index));
  }, { rootMargin: '-25% 0px -55% 0px', threshold: [0.1, 0.3, 0.6] });
  sections.forEach((section) => observer.observe(section));

  restoreDraft();
  applyMoveMode();
  updateSummary();

  if (window.jQuery?.fn?.select2) {
    const propertyTypeSelects = window.jQuery('.property-type-select');
    propertyTypeSelects.select2({
      width: '100%',
      placeholder() {
        return window.jQuery(this).data('placeholder');
      },
      minimumResultsForSearch: 7,
    });
    propertyTypeSelects.on('select2:select select2:clear', function () {
      this.classList.remove('invalid');
      this.dispatchEvent(new Event('change', { bubbles: true }));
    });
  }
})();
