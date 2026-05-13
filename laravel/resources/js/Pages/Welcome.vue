<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
});
</script>

<template>
  <Head title="GymReservas" />
  
  <div style="min-height:100vh;background:#0B0F19;color:#E6E9F0;font-family:system-ui,sans-serif">
    <!-- Navbar -->
    <nav style="position:fixed;top:0;width:100%;background:#121626;border-bottom:1px solid rgba(255,255,255,0.1);padding:1rem 2rem;display:flex;justify-content:space-between;align-items:center;z-index:50">
      <div style="font-weight:bold;font-size:1.25rem"><span style="color:#00F0FF">GYM</span>RESERVAS</div>
      <div style="display:flex;gap:1rem">
        <!-- 🔹 Usamos href normal en lugar de route() para evitar error de Ziggy -->
        <a v-if="canLogin" href="/login" style="padding:0.5rem 1rem;border:1px solid #00F0FF;border-radius:0.5rem;color:#00F0FF;text-decoration:none">Acceder</a>
        <a v-if="canRegister" href="/register" style="padding:0.5rem 1rem;background:#00F0FF;color:#0B0F19;border-radius:0.5rem;font-weight:600;text-decoration:none">Registrarse</a>
      </div>
    </nav>

    <!-- Hero -->
    <main style="padding-top:8rem;text-align:center;max-width:64rem;margin:0 auto;padding:2rem">
      <h1 style="font-size:2.5rem;font-weight:800;margin-bottom:1.5rem;line-height:1.2">
        Reserva tu clase<br/>
        <span style="color:#00F0FF">en tiempo real</span>
      </h1>
      <p style="color:#8B92A8;font-size:1.125rem;margin-bottom:2rem;max-width:48rem;margin-left:auto;margin-right:auto">
        Gestión inteligente de horarios, control de aforo automático y experiencia futurista.
      </p>
      <div style="display:flex;gap:1rem;justify-content:center">
        <a v-if="canRegister" href="/register" style="padding:0.75rem 2rem;background:#00F0FF;color:#0B0F19;border-radius:0.75rem;font-weight:600;text-decoration:none">Comenzar</a>
      </div>
    </main>

    <!-- Footer -->
    <footer style="padding:1.5rem;text-align:center;color:#8B92A8;font-size:0.875rem;border-top:1px solid rgba(255,255,255,0.1);margin-top:4rem">
      <p>© {{ new Date().getFullYear() }} GymReservas - IES La Marisma</p>
    </footer>
  </div>
</template>