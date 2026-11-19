<?php
class PainelController extends Controller
{
    public function index(): void
    {
        $u = $this->exigir();
        $this->view('painel/index', ['u' => $u]);
    }
}