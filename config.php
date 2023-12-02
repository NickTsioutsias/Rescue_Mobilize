<?php
  // Start session on website
  if (session_status() === PHP_SESSION_NONE) {
  session_start();
  }