<?php
// Convenience: visiting http://localhost/pos_tfa3/ forwards to the real front controller in /public.
header('Location: public/', true, 302);
exit;
