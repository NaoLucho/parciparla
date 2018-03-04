-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Mar 21 Novembre 2017 à 15:18
-- Version du serveur :  5.7.20-0ubuntu0.16.04.1
-- Version de PHP :  7.1.11-1+ubuntu16.04.1+deb.sury.org+1

-- NORMALEMENT COMMENCER PAR: (mais je ne sais pas exactement ce que cela fait)
-- SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
-- SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `sf_portail2`
--


DELETE FROM `app_content`;
ALTER TABLE `app_content` AUTO_INCREMENT = 1;
DELETE FROM `app_menu`;
ALTER TABLE `app_menu` AUTO_INCREMENT = 1;
DELETE FROM `app_page`;
ALTER TABLE `app_page` AUTO_INCREMENT = 1;
--DELETE FROM `fos_user_group`;
--ALTER TABLE `fos_user_group` AUTO_INCREMENT = 1;
--DELETE FROM `fos_user_user`;
--ALTER TABLE `fos_user_user` AUTO_INCREMENT = 1;
DELETE FROM `g_list`;
ALTER TABLE `g_list` AUTO_INCREMENT = 1;
DELETE FROM `g_listitem`;
ALTER TABLE `g_listitem` AUTO_INCREMENT = 1;
DELETE FROM `app_contents_rights`;
ALTER TABLE `app_contents_rights` AUTO_INCREMENT = 1;
DELETE FROM `app_menu_page`;
ALTER TABLE `app_menu_page` AUTO_INCREMENT = 1;
DELETE FROM `app_page_content`;
ALTER TABLE `app_page_content` AUTO_INCREMENT = 1;
DELETE FROM `app_pages_rights`;
ALTER TABLE `app_pages_rights` AUTO_INCREMENT = 1;