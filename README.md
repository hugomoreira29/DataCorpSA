# DataCorp S.A. - Room Reservation & Access Control System

> **Academic Disclaimer:** This project was developed as my final project (Projet Intégré Final - PIF) for my professional course at **Lycée du Nord** during the 2022-2023 academic year. It is published here for portfolio and archival purposes.

## Overview

This repository contains the hardware planning, software, and networking configuration for a complete room reservation and physical access control system. The project simulates a corporate environment (DataCorp S.A.) where employees can book conference rooms via a web interface, and physically access those rooms using an RFID badge scanned at a door controller.

## Project Components

The project is divided into several interconnected modules, covering both hardware assembly and software/network engineering:

### 1. Hardware & Workshop (Atelier)
*   **Custom PCB:** Soldered and assembled a custom printed circuit board interfacing with a **Raspberry Pi 4**.
*   **Components:** Integrated an RFID RC522 module, RGB LEDs (PL9823), a push-button, a buzzer, a DS18B20+ temperature sensor, and a 5V Solenoid lock.
*   **Physical Model:** Built a custom physical door model using a PVC mounting plate, custom-bent sheet metal brackets, and a wooden door frame to demonstrate the locking mechanism in real-time.

### 2. Web Application & Database (Labo)
*   **LAMP Stack:** Hosted on an Ubuntu 22.04 LTS Server virtual machine running Apache2, MySQL, and PHP 8.1.
*   **Reservation System:** A custom PHP web application allowing users to register, log in, view available conference rooms, book specific time slots, and manage their reservations. 
*   **Database:** Relational MySQL database managing Users, RFID Badges, User Groups, Rules, Rooms, and Reservations.

### 3. Raspberry Pi Controller
*   **Access Control Script:** A Python script running on the Raspberry Pi that reads RFID badges.
*   **API Integration:** The script sends HTTPS requests to the web server to check if the scanned badge has a valid reservation for that specific time and room.
*   **Hardware Feedback:** 
    *   *Access Granted:* Unlocks the solenoid door, flashes the LED green, and beeps once.
    *   *Access Denied:* Keeps the door locked, flashes the LED red, and beeps twice.
    *   *Temperature Monitoring:* A secondary RGB LED changes color based on the room temperature read by the DS18B20+ sensor.

### 4. Networking & Infrastructure
*   **Network Simulation:** Designed a secure corporate network architecture using Cisco Packet Tracer, implementing specific VLANs (VLAN 100, 110, 120), DHCP, DNS, and Access Control Lists (ACLs) to route and secure traffic between the web server, controllers, and employee laptops.
*   **Automated Backups:** A secondary Backup Server running a Bash script via `cron` to automatically dump and secure the MySQL database every night at 23:00 via FTP.

## Technologies Used
*   **Hardware:** Raspberry Pi 4, Custom PCB, RC522 RFID, Solenoid Lock
*   **Backend:** PHP 8.1, Python 3, Bash/Shell Scripting
*   **Frontend:** HTML, CSS
*   **Database:** MySQL, phpMyAdmin
*   **Infrastructure:** Ubuntu Server 22.04 LTS, Apache2, OpenSSH, vsftpd
*   **Networking:** Cisco Packet Tracer

## Full Documentation

For a comprehensive, step-by-step breakdown of the hardware assembly, network routing tables, database schemas (MCD/MLD), and server configurations, please refer to the official project documentation:

📄 **[Doku_PIF_Moreira_Hugo.pdf](./Doku_PIF_Moreira_Hugo.pdf)**

---
*Created by Hugo Moreira Pereira - 2022/2023*