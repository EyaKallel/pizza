-- Migration: Add missing columns for order pricing and delivery fees
-- Run each line SEPARATELY in phpMyAdmin SQL tab

-- Si 'total' existe déjà, exécute seulement ceci:
ALTER TABLE commandes 
ADD COLUMN frais_livraison DECIMAL(8,2) DEFAULT 0.00;

-- Puis exécute ceci:
ALTER TABLE commande_details 
ADD COLUMN prix_unitaire DECIMAL(8,2) DEFAULT 0.00;
