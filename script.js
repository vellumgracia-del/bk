// ==============================
// Belajar Bersama - script.js
// ==============================

// Konfigurasi Supabase
const SUPABASE_URL = "https://rgntufyuatlkikwuyrxx.supabase.co";
const SUPABASE_ANON_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InJnbnR1Znl1YXRsa2lrd3V5cnh4Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjA0NjY4MjAsImV4cCI6MjA3NjA0MjgyMH0.YgsVxfv3PHiUY1ee2fsLGPFzvQIRlHwD7eBqhs6mm-Y";

let supabase;

// State Aplikasi
const appState = {
  userName: localStorage.getItem("bb_username") || "User" + Math.floor(Math.random() * 1000),
  points: parseInt(localStorage.getItem("bb_points")) || 0,
  currentSubject: null,
};

// Elemen UI
const ui = {
  splash: document.getElementById("splash"),
  sidebar: document.getElementById("sidebar"),
  hamburger: document.getElementById("hamburger"),
  closeSidebar: document.getElementById("closeSidebar"),
  pages: document.querySelectorAll(".page"),
  leaderboardList: document.getElementById("leaderboardList"),
  startLearning: document.getElementById("startLearning"),
  subjectContainer: document.getElementById("subjectContainer"),
  quizContainer: document.getElementById("quizContainer"),
  sendMentor: document.getElementById("sendMentor"),
  mentorInput: document.getElementById("mentorInput"),
  mentorChat: document.getElementById("mentorChat"),
  sendFeedback: document.getElementById("sendFeedback"),
  feedbackInput: document.getElementById("feedbackInput"),
};

// ==============================
// Inisialisasi Aplikasi
// ==============================
window.addEventListener("load", async () => {
  // Sembunyikan splash setelah delay
  setTimeout(() => ui.splash.classList.add("hide"), 1200);

  // Inisialisasi Supabase
  if (window.supabase) {
    supabase = window.supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY);
    console.log("✅ Supabase terhubung.");
    await loadLeaderboard();
  } else {
    console.error("❌ Supabase gagal dimuat.");
  }

  renderSubjects();
});

// ==============================
// Navigasi Halaman
// ==============================
function showPage(pageId) {
  ui.pages.forEach(p => p.classList.remove("active"));
  document.getElementById(pageId).classList.add("active");
  ui.sidebar.classList.remove("open");
}

document.querySelectorAll(".sidebar a").forEach(link => {
  link.addEventListener("click", e => {
    e.preventDefault();
    const page = link.getAttribute("data-page");
    showPage(page);
  });
});

ui.hamburger.addEventListener("click", () => ui.sidebar.classList.add("open"));
ui.closeSidebar.addEventListener("click", () => ui.sidebar.classList.remove("open"));

document.addEventListener("click", e => {
  if (!ui.sidebar.contains(e.target) && !ui.hamburger.contains(e.target)) {
    ui.sidebar.classList.remove("open");
  }
});

// ==============================
// Sistem Pelajaran & Kuis
// ==============================
const subjects = {
  Matematika: [
    { q: "5 + 7 = ?", options: ["10", "11", "12"], answer: "12" },
    { q: "9 × 3 = ?", options: ["27", "21", "24"], answer: "27" },
  ],
  Bahasa: [
    { q: "Sinonim dari 'besar' adalah...", options: ["kecil", "raksasa", "mini"], answer: "raksasa" },
    { q: "Antonim dari 'gelap' adalah...", options: ["terang", "malam", "hitam"], answer: "terang" },
  ],
  Sains: [
    { q: "Air membeku pada suhu...", options: ["0°C", "50°C", "100°C"], answer: "0°C" },
    { q: "Planet terdekat dengan matahari adalah...", options: ["Bumi", "Merkurius", "Venus"], answer: "Merkurius" },
  ],
};

function renderSubjects() {
  ui.subjectContainer.innerHTML = Object.keys(subjects)
    .map(sub => `<div class='subject-card' data-sub='${sub}'>${sub}</div>`) 
    .join("");

  document.querySelectorAll(".subject-card").forEach(card => {
    card.addEventListener("click", () => startQuiz(card.dataset.sub));
  });
}

function startQuiz(subject) {
  appState.currentSubject = subject;
  const quiz = subjects[subject];
  let index = 0;
  let score = 0;

  renderQuestion();

  function renderQuestion() {
    const q = quiz[index];
    ui.subjectContainer.classList.add("hidden");
    ui.quizContainer.classList.remove("hidden");

    ui.quizContainer.innerHTML = `
      <div class='quiz-question'>${q.q}</div>
      ${q.options.map(opt => `<div class='quiz-option'>${opt}</div>`).join("")}
    `;

    document.querySelectorAll(".quiz-option").forEach(opt => {
      opt.addEventListener("click", () => {
        if (opt.textContent === q.answer) score++;
        index++;
        if (index < quiz.length) {
          renderQuestion();
        } else {
          finishQuiz(score);
        }
      });
    });
  }
}

function finishQuiz(score) {
  appState.points += score * 10;
  localStorage.setItem("bb_points", appState.points);
  ui.quizContainer.innerHTML = `<h3>Nilai kamu: ${score * 10}</h3>`;
  saveLeaderboard(appState.userName, appState.points);
  setTimeout(() => {
    ui.quizContainer.classList.add("hidden");
    ui.subjectContainer.classList.remove("hidden");
    showPage("leaderboard");
    loadLeaderboard();
  }, 1500);
}

// ==============================
// Supabase Leaderboard
// ==============================
async function saveLeaderboard(name, points) {
  try {
    const { error } = await supabase.from('leaderboard').upsert({ name, points });
    if (error) throw error;
    console.log("🏆 Skor tersimpan ke Supabase");
  } catch (err) {
    console.error("Gagal menyimpan leaderboard:", err);
  }
}

async function loadLeaderboard() {
  try {
    const { data, error } = await supabase.from('leaderboard').select('*').order('points', { ascending: false }).limit(10);
    if (error) throw error;
    ui.leaderboardList.innerHTML = data.map((row, i) => `
      <li>${i + 1}. ${row.name} — ${row.points} poin</li>
    `).join('');
  } catch (err) {
    console.error("Gagal memuat leaderboard:", err);
  }
}

// ==============================
// Mentor Chat (AI Simulasi)
// ==============================
ui.sendMentor.addEventListener("click", () => {
  const msg = ui.mentorInput.value.trim();
  if (!msg) return;

  ui.mentorChat.innerHTML += `<div><b>Kamu:</b> ${msg}</div>`;
  ui.mentorInput.value = "";

  setTimeout(() => {
    ui.mentorChat.innerHTML += `<div><b>Mentor:</b> Pertanyaan bagus! Coba pelajari lebih lanjut di topik terkait 🌟</div>`;
    ui.mentorChat.scrollTop = ui.mentorChat.scrollHeight;
  }, 1000);
});

// ==============================
// Feedback System (Simpan ke Supabase)
// ==============================
ui.sendFeedback.addEventListener("click", async () => {
  const feedback = ui.feedbackInput.value.trim();
  if (!feedback) return alert("Tulis feedback terlebih dahulu!");

  try {
    const { error } = await supabase.from('feedback').insert({ user: appState.userName, message: feedback });
    if (error) throw error;
    alert("Terima kasih atas feedback kamu!");
    ui.feedbackInput.value = "";
  } catch (err) {
    console.error("Gagal mengirim feedback:", err);
  }
});

// ==============================
// Tombol Mulai Belajar
// ==============================
ui.startLearning.addEventListener("click", () => showPage("belajar"));
