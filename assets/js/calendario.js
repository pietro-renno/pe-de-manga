/**
 * calendario.js — Calendário de eventos Pé de Manga
 * Renderização, navegação e carregamento de fotos via AJAX.
 */

(function () {
  'use strict';

  /* ── Dados injetados pelo PHP ── */
  const EVENTOS = window.PdM_Eventos || [];   // [{id, nome, data_evento, horario, total_fotos}]
  const BASE    = window.PdM_Base    || '';   // caminho base ex: '/pe-de-manga/'

  /* ── Mapa: "YYYY-MM-DD" → [evento, ...] ── */
  const mapaEventos = {};
  EVENTOS.forEach(ev => {
    const key = ev.data_evento.slice(0, 10); // 'YYYY-MM-DD'
    if (!mapaEventos[key]) mapaEventos[key] = [];
    mapaEventos[key].push(ev);
  });

  /* ── Estado ── */
  let mesAtual  = new Date().getMonth();   // 0-indexed
  let anoAtual  = new Date().getFullYear();
  let evIdAtivo = null;

  /* ── Seletores DOM ── */
  const calGrid       = document.getElementById('cal-grid');
  const calMesAno     = document.getElementById('cal-mes-ano');
  const btnAnterior   = document.getElementById('cal-prev');
  const btnProximo    = document.getElementById('cal-next');
  const calEventsList = document.getElementById('cal-events-list');
  const panel         = document.getElementById('cal-panel');
  const panelTitle    = document.getElementById('panel-titulo');
  const panelMeta     = document.getElementById('panel-meta');
  const panelGrid     = document.getElementById('panel-fotos-grid');
  const panelEmpty    = document.getElementById('panel-sem-fotos');
  const panelLoading  = document.getElementById('panel-loading');
  const panelPlaceholder = document.getElementById('panel-placeholder');

  const MESES_PT = [
    'Janeiro','Fevereiro','Março','Abril','Maio','Junho',
    'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'
  ];
  const DIAS_SEMANA = ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'];

  /* ─────────────────────────────────────────────
     RENDERIZAÇÃO DO CALENDÁRIO
  ───────────────────────────────────────────── */
  function renderCalendario(mes, ano) {
    calMesAno.textContent = `${MESES_PT[mes]} ${ano}`;

    const hoje       = new Date();
    const primeiroDia = new Date(ano, mes, 1).getDay(); // 0=Dom
    const diasNoMes  = new Date(ano, mes + 1, 0).getDate();

    // Limpa (mantém o header das letras dos dias)
    const dias = calGrid.querySelectorAll('.cal-day');
    dias.forEach(d => d.remove());

    // Células vazias antes do dia 1
    for (let i = 0; i < primeiroDia; i++) {
      const vazio = document.createElement('div');
      vazio.className = 'cal-day cal-day--vazio';
      calGrid.appendChild(vazio);
    }

    // Dias do mês
    for (let d = 1; d <= diasNoMes; d++) {
      const el   = document.createElement('button');
      el.className = 'cal-day';
      el.type    = 'button';

      const chave = `${ano}-${String(mes + 1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
      const evsDia = mapaEventos[chave] || [];

      // Número
      const numEl = document.createElement('span');
      numEl.className = 'cal-day-num';
      numEl.textContent = d;
      el.appendChild(numEl);

      // Ponto(s) de evento
      if (evsDia.length > 0) {
        el.classList.add('has-event');
        const dot = document.createElement('span');
        dot.className = 'cal-dot';
        el.appendChild(dot);
        el.addEventListener('click', () => aoClicarDia(chave, evsDia, el));
        el.setAttribute('aria-label', `${d} ${MESES_PT[mes]} — ${evsDia.length} evento(s)`);
      } else {
        el.disabled = true;
        el.setAttribute('aria-disabled', 'true');
      }

      // Hoje
      if (d === hoje.getDate() && mes === hoje.getMonth() && ano === hoje.getFullYear()) {
        el.classList.add('today');
      }

      // Ativo (já selecionado)
      if (evIdAtivo) {
        const evAtivo = EVENTOS.find(ev => ev.id === evIdAtivo);
        if (evAtivo && evAtivo.data_evento.slice(0, 10) === chave) {
          el.classList.add('selected');
        }
      }

      calGrid.appendChild(el);
    }
  }

  /* ─────────────────────────────────────────────
     CLIQUE NUM DIA
  ───────────────────────────────────────────── */
  function aoClicarDia(chave, evsDia, elBtn) {
    // Remove selected de todas as células
    calGrid.querySelectorAll('.cal-day.selected').forEach(c => c.classList.remove('selected'));
    elBtn.classList.add('selected');

    if (evsDia.length === 1) {
      // Um único evento: carrega direto
      carregarEvento(evsDia[0].id);
    } else {
      // Múltiplos: exibe mini-lista abaixo do calendário
      mostrarListaDia(evsDia);
    }
  }

  /* Lista de eventos de um dia (quando há mais de 1) */
  function mostrarListaDia(evsDia) {
    calEventsList.innerHTML = '';
    calEventsList.classList.add('visible');

    evsDia.forEach(ev => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'cal-ev-item';
      btn.innerHTML = `
        <span class="cal-ev-item-nome">${esc(ev.nome)}</span>
        ${ev.horario ? `<span class="cal-ev-item-hora">${esc(ev.horario)}</span>` : ''}
        <span class="cal-ev-item-fotos">${ev.total_fotos} foto${ev.total_fotos !== 1 ? 's' : ''}</span>
      `;
      btn.addEventListener('click', () => {
        calEventsList.querySelectorAll('.cal-ev-item').forEach(b => b.classList.remove('ativo'));
        btn.classList.add('ativo');
        carregarEvento(ev.id);
      });
      calEventsList.appendChild(btn);
    });
  }

  /* ─────────────────────────────────────────────
     CARREGAR EVENTO VIA FETCH
  ───────────────────────────────────────────── */
  function carregarEvento(id) {
    evIdAtivo = id;

    // Atualiza URL sem reload
    const url = new URL(window.location.href);
    url.searchParams.set('id', id);
    history.replaceState({ evId: id }, '', url.toString());

    // Mostra loading, esconde resto
    setEstadoPanel('loading');

    fetch(`${BASE}api/evento.php?id=${id}`)
      .then(r => {
        if (!r.ok) throw new Error('Erro ' + r.status);
        return r.json();
      })
      .then(data => {
        preencherPanel(data.evento, data.fotos);
      })
      .catch(() => {
        setEstadoPanel('erro');
      });

    // Em mobile: scroll até o painel
    if (window.innerWidth < 960) {
      setTimeout(() => {
        panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }, 80);
    }
  }

  /* ─────────────────────────────────────────────
     PREENCHER PAINEL DE FOTOS
  ───────────────────────────────────────────── */
  function preencherPanel(ev, fotos) {
    // Título
    panelTitle.textContent = ev.nome;

    // Meta
    const dataFmt = formatarData(ev.data_evento);
    let metaHtml = `<span class="panel-meta-data">${dataFmt}</span>`;
    if (ev.horario) {
      metaHtml += ` <span class="panel-meta-sep">·</span>
        <span class="panel-meta-hora">
          <span class="material-symbols-outlined" style="font-size:.9rem;vertical-align:middle;">schedule</span>
          ${esc(ev.horario)}
        </span>`;
    }
    if (ev.descricao) {
      metaHtml += ` <span class="panel-meta-sep">·</span>
        <span class="panel-meta-desc">${esc(ev.descricao)}</span>`;
    }
    panelMeta.innerHTML = metaHtml;

    // Fotos
    panelGrid.innerHTML = '';

    if (fotos.length === 0) {
      setEstadoPanel('sem-fotos');
      return;
    }

    fotos.forEach(foto => {
      const item = document.createElement('div');
      item.className = 'eventos-foto-item';
      item.setAttribute('role', 'button');
      item.setAttribute('tabindex', '0');
      item.setAttribute('aria-label', foto.descricao || ev.nome);
      item.innerHTML = `
        <img src="${BASE}data/uploads/${esc(foto.arquivo)}"
             alt="${esc(foto.descricao || ev.nome)}" loading="lazy">
        ${foto.descricao ? `<div class="eventos-foto-legenda">${esc(foto.descricao)}</div>` : ''}
      `;
      item.addEventListener('click', () => abrirLightbox(foto.arquivo, foto.descricao || ''));
      item.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') abrirLightbox(foto.arquivo, foto.descricao || ''); });
      panelGrid.appendChild(item);
    });

    setEstadoPanel('fotos');

    // Realça o item ativo na lista de dias
    if (calEventsList.classList.contains('visible')) {
      calEventsList.querySelectorAll('.cal-ev-item').forEach(btn => {
        btn.classList.toggle('ativo', parseInt(btn.dataset.id) === ev.id);
      });
    }
  }

  /* ─────────────────────────────────────────────
     ESTADOS DO PAINEL
  ───────────────────────────────────────────── */
  function setEstadoPanel(estado) {
    panelLoading.hidden     = estado !== 'loading';
    panelEmpty.hidden       = estado !== 'sem-fotos';
    panelGrid.hidden        = estado !== 'fotos';
    panelPlaceholder.hidden = estado !== 'placeholder';

    const header = document.getElementById('panel-header');
    header.hidden = estado === 'placeholder' || estado === 'loading';
  }

  /* ─────────────────────────────────────────────
     NAVEGAÇÃO DE MÊS
  ───────────────────────────────────────────── */
  btnAnterior.addEventListener('click', () => {
    if (mesAtual === 0) { mesAtual = 11; anoAtual--; }
    else mesAtual--;
    calEventsList.innerHTML = '';
    calEventsList.classList.remove('visible');
    renderCalendario(mesAtual, anoAtual);
  });

  btnProximo.addEventListener('click', () => {
    if (mesAtual === 11) { mesAtual = 0; anoAtual++; }
    else mesAtual++;
    calEventsList.innerHTML = '';
    calEventsList.classList.remove('visible');
    renderCalendario(mesAtual, anoAtual);
  });

  /* ─────────────────────────────────────────────
     LIGHTBOX
  ───────────────────────────────────────────── */
  function abrirLightbox(arquivo, legenda) {
    document.getElementById('lightbox-img').src = `${BASE}data/uploads/${arquivo}`;
    document.getElementById('lightbox-cap').textContent = legenda;
    document.getElementById('lightbox').classList.add('open');
  }

  window.fecharLightbox = function () {
    document.getElementById('lightbox').classList.remove('open');
    document.getElementById('lightbox-img').src = '';
  };

  document.getElementById('lightbox')
    .addEventListener('click', function (e) {
      if (e.target === this) window.fecharLightbox();
    });

  /* ─────────────────────────────────────────────
     LIGHTBOX — PROGRAMAÇÃO DO MÊS
  ───────────────────────────────────────────── */
  window.abrirProg = function (src) {
    const lb = document.getElementById('progLightbox');
    document.getElementById('progLightboxImg').src = src;
    lb.style.display = 'flex';
  };

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
      window.fecharLightbox();
      const lb = document.getElementById('progLightbox');
      if (lb) lb.style.display = 'none';
    }
  });

  /* ─────────────────────────────────────────────
     UTILITÁRIOS
  ───────────────────────────────────────────── */
  function esc(str) {
    if (!str) return '';
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function formatarData(dateStr) {
    // "YYYY-MM-DD" → "DD/MM/YYYY"
    const [y, m, d] = dateStr.slice(0, 10).split('-');
    return `${d}/${m}/${y}`;
  }

  /* ─────────────────────────────────────────────
     INICIALIZAÇÃO
  ───────────────────────────────────────────── */
  function init() {
    // Inicializa calendário no mês atual
    renderCalendario(mesAtual, anoAtual);

    // Se URL tem ?id=X, tenta navegar até o mês do evento e carregar
    const urlParams = new URLSearchParams(window.location.search);
    const idParam   = parseInt(urlParams.get('id'));
    if (idParam > 0) {
      const ev = EVENTOS.find(e => e.id === idParam);
      if (ev) {
        // Navega para o mês do evento
        const d = new Date(ev.data_evento + 'T00:00:00');
        mesAtual = d.getMonth();
        anoAtual = d.getFullYear();
        evIdAtivo = idParam;
        renderCalendario(mesAtual, anoAtual);
        carregarEvento(idParam);
        return;
      }
    }

    // Sem ?id: tenta o evento mais recente
    if (EVENTOS.length > 0) {
      const mais_recente = EVENTOS[0]; // já ordenado DESC por data
      const d = new Date(mais_recente.data_evento + 'T00:00:00');
      mesAtual = d.getMonth();
      anoAtual = d.getFullYear();
      evIdAtivo = mais_recente.id;
      renderCalendario(mesAtual, anoAtual);
      carregarEvento(mais_recente.id);
    } else {
      setEstadoPanel('placeholder');
    }
  }

  init();
})();
