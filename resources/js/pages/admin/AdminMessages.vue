<template>
    <div>
        <h1 class="admin-title mb-6">{{ t('admin.messages') }}</h1>

        <div v-if="loading" class="text-muted">{{ t('common.loading') }}</div>
        <div v-else-if="!messages.length" class="text-muted">Sin mensajes.</div>
        <div v-else class="space-y-3">
            <div
                v-for="msg in messages"
                :key="msg.id"
                class="message-item"
                :class="{ unread: !msg.read }"
            >
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 mb-1 flex-wrap">
                            <span class="msg-name">{{ msg.name }}</span>
                            <span class="msg-email">{{ msg.email }}</span>
                            <a v-if="msg.phone" :href="`tel:${msg.phone}`" class="msg-phone">
                                📞 {{ msg.phone }}
                            </a>
                            <span v-if="!msg.read" class="unread-badge">nuevo</span>
                        </div>
                        <div class="msg-subject">{{ msg.subject }}</div>
                        <p class="msg-body mt-1">{{ msg.message }}</p>
                        <div class="msg-date">{{ formatDate(msg.created_at) }}</div>

                        <div v-if="replyingTo === msg.id" class="reply-form mt-3">
                            <textarea
                                v-model="replyBody"
                                class="reply-textarea"
                                rows="4"
                                placeholder="Escribe tu respuesta..."
                                required
                            ></textarea>
                            <div class="flex gap-2 mt-2">
                                <button @click="sendReply(msg)" :disabled="sending" class="action-btn reply-send">
                                    {{ sending ? 'Enviando…' : '✉ Enviar respuesta' }}
                                </button>
                                <button @click="cancelReply" class="action-btn">Cancelar</button>
                            </div>
                            <p v-if="replyError" class="reply-error">{{ replyError }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1 flex-shrink-0">
                        <button v-if="!msg.read" @click="markRead(msg)" class="action-btn">✓ {{ t('admin.mark_read') }}</button>
                        <button @click="startReply(msg)" class="action-btn reply-btn">✉ Responder</button>
                        <button @click="deleteMsg(msg)" class="action-btn danger">🗑</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import axios from 'axios';

const { t } = useI18n();
const messages = ref([]);
const loading = ref(true);
const replyingTo = ref(null);
const replyBody = ref('');
const sending = ref(false);
const replyError = ref('');

function formatDate(d) {
    return new Date(d).toLocaleString('es-ES', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
}

async function load() {
    const { data } = await axios.get('/api/admin/messages');
    messages.value = data;
}

onMounted(async () => {
    try { await load(); } finally { loading.value = false; }
});

async function markRead(msg) {
    await axios.patch(`/api/admin/messages/${msg.id}/read`);
    await load();
}

async function deleteMsg(msg) {
    if (!confirm(t('admin.confirm_delete'))) return;
    await axios.delete(`/api/admin/messages/${msg.id}`);
    await load();
}

function startReply(msg) {
    replyingTo.value = msg.id;
    replyBody.value = '';
}

function cancelReply() {
    replyingTo.value = null;
    replyBody.value = '';
    replyError.value = '';
}

async function sendReply(msg) {
    if (!replyBody.value.trim()) return;
    sending.value = true;
    replyError.value = '';
    try {
        await axios.post(`/api/admin/messages/${msg.id}/reply`, { body: replyBody.value });
        cancelReply();
        await load();
    } catch (err) {
        replyError.value = err.response?.data?.message ?? 'No se pudo enviar el correo.';
    } finally {
        sending.value = false;
    }
}
</script>

<style scoped>
.admin-title { font-family:'Oswald',sans-serif; font-weight:700; font-size:1.75rem; letter-spacing:0.1em; text-transform:uppercase; border-left:4px solid #A51C30; padding-left:1rem; }
.message-item { background:#1A1A1A; padding:1rem; border-left:3px solid #2A2A2A; }
.message-item.unread { border-left-color:#A51C30; }
.msg-name { font-family:'Oswald',sans-serif; font-weight:700; font-size:0.9rem; color:#EDE8D8; }
.msg-email { font-size:0.8rem; color:#B5AFA0; }
.msg-phone { font-family:'JetBrains Mono',monospace; font-size:0.78rem; color:#A51C30; text-decoration:none; }
.msg-phone:hover { text-decoration:underline; }
.msg-subject { font-family:'Oswald',sans-serif; font-size:0.85rem; text-transform:uppercase; letter-spacing:0.05em; color:#A51C30; }
.msg-body { font-size:0.875rem; color:#B5AFA0; white-space:pre-wrap; line-height:1.5; }
.msg-date { font-family:'JetBrains Mono',monospace; font-size:0.7rem; color:#B5AFA0; margin-top:0.5rem; }
.unread-badge { background:#A51C30; color:#EDE8D8; font-family:'JetBrains Mono',monospace; font-size:0.65rem; text-transform:uppercase; padding:0.1rem 0.35rem; }
.action-btn { font-family:'Oswald',sans-serif; font-size:0.7rem; text-transform:uppercase; letter-spacing:0.05em; padding:0.3rem 0.6rem; background:#2A2A2A; color:#EDE8D8; border:none; cursor:pointer; white-space:nowrap; }
.action-btn.danger { color:#C0392B; }
.action-btn.reply-btn { color:#A51C30; }
.action-btn.reply-send { background:#A51C30; color:#EDE8D8; }
.action-btn:disabled { opacity:0.5; cursor:not-allowed; }
.reply-form { border-top:1px solid #2A2A2A; padding-top:0.75rem; }
.reply-textarea { width:100%; background:#111; border:1px solid #2A2A2A; color:#EDE8D8; padding:0.5rem; font-family:'JetBrains Mono',monospace; font-size:0.8rem; resize:vertical; }
.reply-error { color:#C0392B; font-family:'JetBrains Mono',monospace; font-size:0.75rem; margin-top:0.5rem; }
.text-muted { color:#B5AFA0; }
</style>
