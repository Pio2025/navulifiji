<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navuli Fiji - Coming Soon | Revolutionary School Management System</title>
    <meta name="description" content="This School Management System is purpose-built to align with the Fiji National Curriculum, integrating key elements like student assessment, lesson planning, and resource management into a single platform." />
	<meta name="keywords" content="navuli, fiji school, fiji education, education fiji, school management system, school management information system, fiji school managemenet information system, elearn fiji, ministry of education fiji" />
	<meta name="google-site-verification" content="Rq9FO3txj3m8uSunynz5FK5fwQfkZJo3Qv93cIGPzc-E" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta property="og:locale" content="en_US" />
	<meta property="og:type" content="article" />
	<meta property="og:title" content="Navuli - School Management Information System" />
	<meta property="og:url" content="https://navulifiji.com" />
	<meta property="og:site_name" content="Navuli" />
	<link rel="canonical" href="http://navulifiji.com" />
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800;900&family=Quicksand:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --primary-blue: #0066cc;
            --secondary-blue: #0099ff;
            --dark-blue: #003366;
            --gold: #FFD700;
            --light-blue: #e6f7ff;
            --gradient-1: linear-gradient(135deg, #0066cc 0%, #0099ff 100%);
            --gradient-2: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            --gradient-3: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        }
        
        body {
            font-family: 'Inter', 'Poppins', sans-serif;
            overflow-x: hidden;
            background: linear-gradient(135deg, #0a4d8f 0%, #0066cc 50%, #0099ff 100%);
            min-height: 100vh;
            position: relative;
        }
        
        /* Minimal Background Effects (20%) */
        .background-minimal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            overflow: hidden;
            pointer-events: none;
            opacity: 0.3;
        }
        
        /* Subtle Gradient Orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.25;
            animation: float-orb 25s ease-in-out infinite;
        }
        
        .orb-1 {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(0, 153, 255, 0.4) 0%, transparent 70%);
            top: -15%;
            left: -15%;
        }
        
        .orb-2 {
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255, 215, 0, 0.3) 0%, transparent 70%);
            bottom: -15%;
            right: -15%;
            animation-delay: 10s;
        }
        
        @keyframes float-orb {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -30px) scale(1.05); }
            66% { transform: translate(-30px, 30px) scale(0.95); }
        }
        
        /* Subtle Ocean Wave (Bottom Only) */
        .ocean-wave {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 120px;
            z-index: 2;
            opacity: 0.2;
        }
        
        .logo-circle {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #ffffff, #f0f8ff);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 
                0 15px 50px rgba(0, 0, 0, 0.3),
                0 0 0 8px rgba(255, 255, 255, 0.1);
            animation: logo-pulse 3s ease-in-out infinite;
            position: relative;
            overflow: visible; /* Changed from hidden to visible */
        }
        
        .logo-circle img {
            width: 70px;
            height: 70px;
            object-fit: contain;
            position: relative;
            z-index: 2;
            filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.1));
        }
        
        .error-msg {
            display: none;
            padding: 16px 22px;
            background: linear-gradient(135deg, #f44336, #d32f2f);
            color: white;
            border-radius: 14px;
            margin-top: 18px;
            font-weight: 600;
            box-shadow: 0 6px 22px rgba(244, 67, 54, 0.3);
            animation: slideIn 0.5s ease-out;
        }
        
        .error-msg i {
            font-size: 1.4rem;
        }
        
        .wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 200%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.15" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: 50% 100%;
            animation: wave 20s linear infinite;
        }
        
        @keyframes wave {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        
        /* Minimal Floating Particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 3;
            pointer-events: none;
            opacity: 0.4;
        }
        
        .particle {
            position: absolute;
            width: 6px;
            height: 6px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            animation: float-up 20s infinite ease-in;
        }
        
        @keyframes float-up {
            0% { transform: translateY(100vh) scale(0); opacity: 0; }
            10% { opacity: 0.6; }
            90% { opacity: 0.6; }
            100% { transform: translateY(-100vh) scale(1); opacity: 0; }
        }
        
        /* Main Container (80% Focus) */
        .container {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            padding: 30px 15px;
            max-width: 1600px;
            margin: 0 auto;
        }
        
        /* Compact Header */
        .header {
            text-align: center;
            padding: 15px;
            margin-bottom: 30px;
            animation: fadeInDown 0.8s ease-out;
        }
        
        .logo-container {
            display: inline-block;
            position: relative;
        }
        
        .logo-circle {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #ffffff, #f0f8ff);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 
                0 15px 50px rgba(0, 0, 0, 0.3),
                0 0 0 8px rgba(255, 255, 255, 0.1);
            animation: logo-pulse 3s ease-in-out infinite;
            position: relative;
            overflow: hidden;
        }
        
        .logo-circle::before {
            content: '';
            position: absolute;
            width: 150%;
            height: 150%;
            background: conic-gradient(
                transparent,
                rgba(255, 215, 0, 0.3),
                transparent 30%
            );
            animation: rotate 4s linear infinite;
        }
        
        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .logo-circle::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #ffffff, #f0f8ff);
            border-radius: 50%;
            transform: scale(0.88);
        }
        
        .logo-circle i {
            position: relative;
            z-index: 2;
            font-size: 45px;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        @keyframes logo-pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .brand-title {
            font-family: 'Quicksand', sans-serif;
            font-size: 3.5rem;
            font-weight: 900;
            color: #ffffff;
            text-shadow: 
                0 4px 15px rgba(0, 0, 0, 0.3),
                0 0 30px rgba(255, 255, 255, 0.2);
            margin-bottom: 5px;
            letter-spacing: 3px;
        }
        
        .brand-title .fiji {
            color: #FFD700;
            text-shadow: 
                0 0 25px rgba(255, 215, 0, 0.8),
                0 0 50px rgba(255, 215, 0, 0.4);
            animation: glow-pulse 2s ease-in-out infinite;
        }
        
        @keyframes glow-pulse {
            0%, 100% { 
                text-shadow: 
                    0 0 25px rgba(255, 215, 0, 0.8),
                    0 0 50px rgba(255, 215, 0, 0.4);
            }
            50% { 
                text-shadow: 
                    0 0 40px rgba(255, 215, 0, 1),
                    0 0 80px rgba(255, 215, 0, 0.6);
            }
        }
        
        .brand-subtitle {
            font-size: 1.1rem;
            color: #cce7ff;
            font-weight: 500;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        
        /* Main Grid - More Compact */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            max-width: 1500px;
            margin: 0 auto;
            animation: fadeInUp 1s ease-out;
        }
        
        /* Enhanced Card with More Prominence */
        .card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 25px;
            padding: 35px;
            box-shadow: 
                0 25px 80px rgba(0, 0, 0, 0.35),
                0 0 0 1px rgba(255, 255, 255, 0.2) inset,
                0 0 50px rgba(0, 102, 204, 0.1);
            backdrop-filter: blur(20px);
            position: relative;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.4),
                transparent
            );
            transition: left 0.6s;
        }
        
        .card:hover::before {
            left: 100%;
        }
        
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 
                0 35px 100px rgba(0, 0, 0, 0.4),
                0 0 0 1px rgba(255, 255, 255, 0.3) inset,
                0 0 80px rgba(0, 102, 204, 0.2);
        }
        
        /* Card Accent Bar - Thicker */
        .card-accent {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: var(--gradient-1);
            box-shadow: 0 6px 25px rgba(0, 102, 204, 0.5);
        }
        
        .card-accent.gold {
            background: var(--gradient-2);
            box-shadow: 0 6px 25px rgba(255, 215, 0, 0.5);
        }
        
        /* Card Titles - Larger */
        .card-title {
            font-size: 2rem;
            font-weight: 800;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 12px;
            text-align: center;
            position: relative;
            display: inline-block;
            width: 100%;
        }
        
        .card-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: var(--gradient-1);
            border-radius: 2px;
        }
        
        .card-subtitle {
            text-align: center;
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 25px;
            font-weight: 500;
        }
        
        /* Student Animation - Optimized Size */
        .student-animation {
            position: relative;
            height: 320px;
            background: linear-gradient(135deg, #f0f8ff 0%, #e6f2ff 50%, #cce7ff 100%);
            border-radius: 18px;
            margin-bottom: 30px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 
                0 8px 35px rgba(0, 102, 204, 0.2) inset,
                0 8px 25px rgba(0, 0, 0, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.6);
        }
        
        /* Animated Background Pattern */
        .student-animation::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 200%;
            height: 200%;
            background-image: 
                linear-gradient(45deg, rgba(0, 102, 204, 0.04) 25%, transparent 25%),
                linear-gradient(-45deg, rgba(0, 102, 204, 0.04) 25%, transparent 25%),
                linear-gradient(45deg, transparent 75%, rgba(0, 102, 204, 0.04) 75%),
                linear-gradient(-45deg, transparent 75%, rgba(0, 102, 204, 0.04) 75%);
            background-size: 30px 30px;
            background-position: 0 0, 0 15px, 15px -15px, -15px 0px;
            animation: move-pattern 12s linear infinite;
        }
        
        @keyframes move-pattern {
            0% { transform: translate(0, 0); }
            100% { transform: translate(30px, 30px); }
        }
        
        .student-figure {
            position: relative;
            width: 150px;
            height: 200px;
            z-index: 2;
        }
        
        /* Student Head */
        .student-head {
            width: 65px;
            height: 65px;
            background: linear-gradient(135deg, #d4a574, #c49563);
            border-radius: 50%;
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
            border: 3px solid rgba(255, 255, 255, 0.5);
        }
        
        .student-head::before {
            content: '😊';
            font-size: 45px;
            position: absolute;
            top: 8px;
            left: 10px;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        }
        
        /* Student Hair */
        .student-hair {
            width: 75px;
            height: 38px;
            background: linear-gradient(135deg, #2c1810, #1a0f08);
            border-radius: 50% 50% 0 0;
            position: absolute;
            top: -8px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.3);
        }
        
        /* Student Body */
        .student-body {
            width: 85px;
            height: 95px;
            background: var(--gradient-1);
            border-radius: 12px 12px 0 0;
            position: absolute;
            top: 60px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 6px 18px rgba(0, 102, 204, 0.3);
        }
        
        /* Student Arms */
        .student-arm {
            width: 16px;
            height: 75px;
            background: linear-gradient(135deg, #d4a574, #c49563);
            border-radius: 12px;
            position: absolute;
            top: 65px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
        }
        
        .student-arm.left {
            left: 12px;
            animation: type-left 1s ease-in-out infinite;
        }
        
        .student-arm.right {
            right: 12px;
            animation: type-right 1s ease-in-out infinite;
            animation-delay: 0.5s;
        }
        
        @keyframes type-left {
            0%, 100% { transform: rotate(-10deg); }
            50% { transform: rotate(-25deg); }
        }
        
        @keyframes type-right {
            0%, 100% { transform: rotate(10deg); }
            50% { transform: rotate(25deg); }
        }
        
        /* Laptop */
        .laptop {
            position: absolute;
            bottom: 25px;
            left: 50%;
            transform: translateX(-50%);
            width: 150px;
            height: 95px;
            filter: drop-shadow(0 12px 25px rgba(0, 0, 0, 0.3));
        }
        
        .laptop-screen {
            width: 150px;
            height: 85px;
            background: linear-gradient(135deg, #1a1a1a, #0d0d0d);
            border-radius: 8px;
            border: 4px solid #333;
            position: relative;
            overflow: hidden;
            box-shadow: 
                0 0 0 2px rgba(255, 255, 255, 0.1) inset,
                0 8px 25px rgba(0, 0, 0, 0.5);
        }
        
        .laptop-screen::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                135deg,
                rgba(255, 255, 255, 0.1) 0%,
                transparent 50%,
                rgba(255, 255, 255, 0.05) 100%
            );
        }
        
        .laptop-content {
            padding: 12px;
            color: #00ff00;
            font-family: 'Courier New', monospace;
            font-size: 9px;
            line-height: 1.4;
            position: relative;
            z-index: 2;
            text-shadow: 0 0 5px rgba(0, 255, 0, 0.5);
        }
        
        .typing-text {
            border-right: 2px solid #00ff00;
            animation: blink 0.7s step-end infinite;
        }
        
        @keyframes blink {
            0%, 100% { border-color: #00ff00; }
            50% { border-color: transparent; }
        }
        
        .laptop-base {
            width: 160px;
            height: 12px;
            background: linear-gradient(135deg, #666, #999, #666);
            border-radius: 0 0 8px 8px;
            position: absolute;
            bottom: 0;
            left: -5px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }
        
        /* Floating Math Equations */
        .math-equation {
            position: absolute;
            font-size: 26px;
            font-weight: 800;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: float-equation 4s ease-in-out infinite;
            opacity: 0;
            filter: drop-shadow(0 2px 6px rgba(0, 102, 204, 0.3));
        }
        
        .math-equation:nth-child(1) { 
            top: 10%; 
            right: 10%; 
            animation-delay: 0s; 
        }
        
        .math-equation:nth-child(2) { 
            top: 32%; 
            right: 15%; 
            animation-delay: 1.3s; 
            font-size: 22px;
        }
        
        .math-equation:nth-child(3) { 
            top: 55%; 
            right: 8%; 
            animation-delay: 2.6s; 
            font-size: 24px;
        }
        
        @keyframes float-equation {
            0% { opacity: 0; transform: translateY(0) rotate(0deg); }
            15% { opacity: 1; }
            85% { opacity: 1; }
            100% { opacity: 0; transform: translateY(-110px) rotate(12deg); }
        }
        
        /* Quiz Container - More Compact */
        .quiz-container {
            background: linear-gradient(135deg, #ffffff, #f8fcff);
            padding: 28px;
            border-radius: 18px;
            box-shadow: 
                0 8px 35px rgba(0, 0, 0, 0.08),
                0 0 0 1px rgba(0, 102, 204, 0.1);
            border: 2px solid rgba(0, 102, 204, 0.1);
        }
        
        .quiz-progress {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 22px;
        }
        
        .progress-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #e0e0e0;
            transition: all 0.3s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }
        
        .progress-dot.active {
            background: var(--gradient-1);
            transform: scale(1.3);
            box-shadow: 0 0 15px rgba(0, 102, 204, 0.6);
        }
        
        .progress-dot.completed {
            background: var(--gradient-3);
            box-shadow: 0 0 15px rgba(76, 175, 80, 0.6);
        }
        
        .quiz-question {
            font-size: 1.4rem;
            font-weight: 700;
            color: #003366;
            margin-bottom: 22px;
            text-align: center;
            padding: 18px;
            background: linear-gradient(135deg, #f0f8ff, #e6f2ff);
            border-radius: 14px;
            border-left: 5px solid var(--primary-blue);
            box-shadow: 0 3px 12px rgba(0, 102, 204, 0.1);
        }
        
        .quiz-options {
            display: grid;
            gap: 14px;
            margin-bottom: 22px;
        }
        
        .quiz-option {
            padding: 16px 22px;
            background: linear-gradient(135deg, #ffffff, #f8fcff);
            border: 3px solid #e0e0e0;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 600;
            text-align: center;
            font-size: 1.05rem;
            position: relative;
            overflow: hidden;
        }
        
        .quiz-option::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 102, 204, 0.1), transparent);
            transition: left 0.5s;
        }
        
        .quiz-option:hover::before {
            left: 100%;
        }
        
        .quiz-option:hover {
            background: linear-gradient(135deg, #f0f8ff, #e6f2ff);
            border-color: var(--primary-blue);
            transform: translateX(6px) scale(1.02);
            box-shadow: 
                0 6px 22px rgba(0, 102, 204, 0.15),
                0 0 0 3px rgba(0, 102, 204, 0.05);
        }
        
        .quiz-option.selected {
            background: var(--gradient-1);
            color: white;
            border-color: var(--primary-blue);
            box-shadow: 
                0 8px 25px rgba(0, 102, 204, 0.3),
                0 0 0 3px rgba(0, 102, 204, 0.1);
        }
        
        .quiz-option.correct {
            background: var(--gradient-3);
            color: white;
            border-color: #4CAF50;
            animation: correctPulse 0.6s ease;
            box-shadow: 0 8px 25px rgba(76, 175, 80, 0.4);
        }
        
        .quiz-option.correct::after {
            content: '✓';
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 26px;
            font-weight: bold;
            animation: checkPop 0.5s ease;
        }
        
        @keyframes checkPop {
            0% { transform: translateY(-50%) scale(0); }
            50% { transform: translateY(-50%) scale(1.3); }
            100% { transform: translateY(-50%) scale(1); }
        }
        
        .quiz-option.incorrect {
            background: linear-gradient(135deg, #f44336, #d32f2f);
            color: white;
            border-color: #f44336;
            animation: shake 0.6s ease;
            box-shadow: 0 8px 25px rgba(244, 67, 54, 0.4);
        }
        
        .quiz-option.incorrect::after {
            content: '✗';
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 26px;
            font-weight: bold;
        }
        
        @keyframes correctPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-10px); }
            40% { transform: translateX(10px); }
            60% { transform: translateX(-8px); }
            80% { transform: translateX(8px); }
        }
        
        .quiz-btn {
            width: 100%;
            padding: 16px;
            background: var(--gradient-1);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 1.15rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 22px rgba(0, 102, 204, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .quiz-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .quiz-btn:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .quiz-btn:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 102, 204, 0.5);
        }
        
        .quiz-btn:active:not(:disabled) {
            transform: translateY(-1px);
        }
        
        .quiz-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        /* Quiz Results - Compact */
        .quiz-results {
            display: none;
            text-align: center;
            padding: 35px 28px;
            background: linear-gradient(135deg, #f0fff4 0%, #c6f6d5 100%);
            border-radius: 18px;
            animation: slideIn 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            border: 3px solid rgba(76, 175, 80, 0.3);
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(76, 175, 80, 0.2);
        }
        
        .quiz-results.show {
            display: block;
        }
        
        .result-badge {
            display: inline-block;
            padding: 8px 22px;
            background: var(--gradient-3);
            color: white;
            border-radius: 50px;
            font-weight: 700;
            margin-bottom: 18px;
            box-shadow: 0 4px 18px rgba(76, 175, 80, 0.3);
            font-size: 0.9rem;
        }
        
        .result-score {
            font-size: 4.5rem;
            font-weight: 900;
            background: var(--gradient-3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 18px 0;
            filter: drop-shadow(0 4px 12px rgba(76, 175, 80, 0.3));
        }
        
        .result-message {
            font-size: 1.7rem;
            color: #2d3748;
            margin-bottom: 12px;
            font-weight: 700;
        }
        
        .result-stars {
            font-size: 3rem;
            margin: 20px 0;
        }
        
        .result-stars .star {
            display: inline-block;
            animation: starPop 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
            opacity: 0;
            margin: 0 4px;
        }
        
        .result-stars .star:nth-child(1) { animation-delay: 0.1s; }
        .result-stars .star:nth-child(2) { animation-delay: 0.25s; }
        .result-stars .star:nth-child(3) { animation-delay: 0.4s; }
        
        @keyframes starPop {
            0% { 
                opacity: 0; 
                transform: scale(0) rotate(0deg); 
            }
            50% { 
                transform: scale(1.3) rotate(180deg); 
            }
            100% { 
                opacity: 1; 
                transform: scale(1) rotate(360deg); 
            }
        }
        
        .result-details {
            display: flex;
            justify-content: center;
            gap: 25px;
            margin: 22px 0;
            flex-wrap: wrap;
        }
        
        .result-stat {
            text-align: center;
        }
        
        .result-stat-value {
            font-size: 2.3rem;
            font-weight: 800;
            color: #4CAF50;
        }
        
        .result-stat-label {
            font-size: 0.85rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 4px;
        }
        
        /* Countdown - Compact */
        .countdown {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin: 35px 0;
        }
        
        .countdown-item {
            background: var(--gradient-1);
            padding: 22px 18px;
            border-radius: 18px;
            text-align: center;
            box-shadow: 
                0 12px 30px rgba(0, 102, 204, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.1) inset;
            animation: pulse-countdown 2s ease-in-out infinite;
            position: relative;
            overflow: hidden;
        }
        
        .countdown-item::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent 30%
            );
            animation: rotate 4s linear infinite;
        }
        
        .countdown-item:nth-child(1) { animation-delay: 0s; }
        .countdown-item:nth-child(2) { animation-delay: 0.25s; }
        .countdown-item:nth-child(3) { animation-delay: 0.5s; }
        .countdown-item:nth-child(4) { animation-delay: 0.75s; }
        
        @keyframes pulse-countdown {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .countdown-value {
            font-size: 2.8rem;
            font-weight: 900;
            color: #ffffff;
            display: block;
            position: relative;
            z-index: 2;
            text-shadow: 0 3px 8px rgba(0, 0, 0, 0.3);
        }
        
        .countdown-label {
            font-size: 0.8rem;
            color: #cce7ff;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 6px;
            font-weight: 600;
            position: relative;
            z-index: 2;
        }
        
        /* Features - More Compact */
        .features {
            margin: 30px 0;
        }
        
        .feature-item {
            display: flex;
            align-items: start;
            padding: 22px;
            margin-bottom: 16px;
            background: linear-gradient(135deg, #ffffff, #f8fcff);
            border-radius: 16px;
            border-left: 5px solid var(--primary-blue);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: slideInRight 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            opacity: 0;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }
        
        .feature-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: var(--gradient-1);
            transition: width 0.4s ease;
        }
        
        .feature-item:hover::before {
            width: 100%;
            opacity: 0.05;
        }
        
        .feature-item:nth-child(1) { animation-delay: 0.2s; }
        .feature-item:nth-child(2) { animation-delay: 0.35s; }
        .feature-item:nth-child(3) { animation-delay: 0.5s; }
        .feature-item:nth-child(4) { animation-delay: 0.65s; }
        
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(40px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .feature-item:hover {
            transform: translateX(10px) scale(1.02);
            box-shadow: 
                0 10px 30px rgba(0, 102, 204, 0.15),
                0 0 0 1px rgba(0, 102, 204, 0.1);
            border-left-width: 8px;
        }
        
        .feature-icon {
            font-size: 2.3rem;
            margin-right: 18px;
            min-width: 45px;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            z-index: 2;
        }
        
        .feature-content h4 {
            font-size: 1.15rem;
            color: #003366;
            margin-bottom: 6px;
            font-weight: 700;
            position: relative;
            z-index: 2;
        }
        
        .feature-content p {
            font-size: 0.92rem;
            color: #666;
            line-height: 1.6;
            position: relative;
            z-index: 2;
        }
        
        /* Email Form - Compact */
        .email-form {
            margin-top: 30px;
        }
        
        .form-title {
            font-size: 1.4rem;
            color: #003366;
            margin-bottom: 22px;
            font-weight: 700;
            text-align: center;
        }
        
        .form-group {
            display: flex;
            gap: 12px;
            margin-bottom: 18px;
        }
        
        .form-input {
            flex: 1;
            padding: 16px 22px;
            border: 3px solid #e0e0e0;
            border-radius: 14px;
            font-size: 1.02rem;
            outline: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .form-input:focus {
            border-color: var(--primary-blue);
            box-shadow: 
                0 0 0 3px rgba(0, 102, 204, 0.1),
                0 4px 18px rgba(0, 102, 204, 0.15);
            transform: translateY(-2px);
        }
        
        .form-btn {
            padding: 16px 35px;
            background: var(--gradient-2);
            color: #003366;
            border: none;
            border-radius: 14px;
            font-size: 1.05rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 22px rgba(255, 215, 0, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .form-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .form-btn:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .form-btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(255, 215, 0, 0.5);
        }
        
        .form-btn:active {
            transform: translateY(-2px);
        }
        
        .success-msg {
            display: none;
            padding: 16px 22px;
            background: var(--gradient-3);
            color: white;
            border-radius: 14px;
            margin-top: 18px;
            animation: slideIn 0.5s ease-out;
            font-weight: 600;
            box-shadow: 0 6px 22px rgba(76, 175, 80, 0.3);
        }
        
        .success-msg.show {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .success-msg i {
            font-size: 1.4rem;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-25px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Compact Footer */
        .footer {
            text-align: center;
            padding: 35px 20px 25px;
            color: #cce7ff;
            margin-top: 50px;
            position: relative;
            z-index: 10;
        }
        
        .social-links {
            margin-bottom: 20px;
        }
        
        .social-links a {
            display: inline-block;
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 50%;
            color: #ffffff;
            font-size: 1.3rem;
            line-height: 48px;
            margin: 0 8px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.2);
        }
        
        .social-links a:hover {
            background: var(--gradient-2);
            color: #003366;
            transform: translateY(-6px) scale(1.1);
            box-shadow: 0 8px 25px rgba(255, 215, 0, 0.4);
        }
        
        /* Animations */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* NEW: Scroll to Top Button */
        .scroll-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--gradient-1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0, 102, 204, 0.4);
            z-index: 1000;
        }
        
        .scroll-to-top.show {
            opacity: 1;
            visibility: visible;
        }
        
        .scroll-to-top:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 102, 204, 0.6);
        }
        
        /* NEW: Loading Indicator */
        .loading-indicator {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--gradient-1);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
            z-index: 9999;
        }
        
        /* NEW: Toast Notification */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 18px 25px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            opacity: 0;
            transform: translateX(400px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 10000;
            border-left: 4px solid var(--primary-blue);
        }
        
        .toast.show {
            opacity: 1;
            transform: translateX(0);
        }
        
        .toast.success {
            border-left-color: #4CAF50;
        }
        
        .toast.error {
            border-left-color: #f44336;
        }
        
        .toast-icon {
            font-size: 1.5rem;
        }
        
        .toast-message {
            font-weight: 600;
            color: #333;
        }
        
        /* Responsive */
        @media (max-width: 1200px) {
            .main-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }
        }
        
        @media (max-width: 768px) {
            .brand-title {
                font-size: 2.5rem;
            }
            
            .card-title {
                font-size: 1.7rem;
            }
            
            .student-animation {
                height: 280px;
            }
            
            .countdown {
                grid-template-columns: repeat(2, 1fr);
                gap: 14px;
            }
            
            .countdown-value {
                font-size: 2.2rem;
            }
            
            .form-group {
                flex-direction: column;
            }
            
            .form-btn {
                width: 100%;
            }
            
            .result-score {
                font-size: 3.5rem;
            }
            
            .card {
                padding: 28px 22px;
            }
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 20px 12px;
            }
            
            .card {
                padding: 25px 18px;
            }
            
            .brand-title {
                font-size: 2rem;
            }
            
            .card-title {
                font-size: 1.5rem;
            }
            
            .countdown-value {
                font-size: 1.9rem;
            }
        }
    </style>
</head>
<body>
    <!-- Loading Indicator -->
    <div class="loading-indicator" id="loadingIndicator"></div>
    
    <!-- Minimal Background Effects (20%) -->
    <div class="background-minimal">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
    </div>
    
    <!-- Subtle Ocean Wave -->
    <div class="ocean-wave">
        <div class="wave"></div>
    </div>
    
    <!-- Minimal Floating Particles -->
    <div class="particles" id="particles"></div>
    
    <!-- Main Container (80% Focus) -->
    <div class="container">
        <!-- Compact Header -->
        <div class="header">
            <div class="logo-container">
                <div class="logo-circle">
                    <img src="<?php echo base_url(); ?>icon.png" alt="Navuli Fiji Logo" style="width: 60px; height: 60px; position: relative; z-index: 2; object-fit: contain;">
                </div>
            </div>
            <h1 class="brand-title">
                Navuli <span class="fiji">Fiji</span>
            </h1>
            <p class="brand-subtitle">School Management Information System</p>
        </div>
        
        <!-- Main Grid -->
        <div class="main-grid">
            <!-- Left: Interactive Demo Card -->
            <div class="card">
                <div class="card-accent"></div>
                <h2 class="card-title">
                    <i class="fas fa-laptop-code"></i> Smart Learning Demo
                </h2>
                <p class="card-subtitle">See how digital education transforms assessment instantly</p>
                
                <!-- Student Animation -->
                <div class="student-animation">
                    <div class="student-figure">
                        <div class="student-hair"></div>
                        <div class="student-head"></div>
                        <div class="student-body"></div>
                        <div class="student-arm left"></div>
                        <div class="student-arm right"></div>
                        
                        <div class="laptop">
                            <div class="laptop-screen">
                                <div class="laptop-content">
                                    <div id="typingText" class="typing-text"></div>
                                </div>
                            </div>
                            <div class="laptop-base"></div>
                        </div>
                    </div>
                    
                    <!-- Floating Math Equations -->
                    <div class="math-equation">2 + 2 = 4</div>
                    <div class="math-equation">5 × 3 = 15</div>
                    <div class="math-equation">10 ÷ 2 = 5</div>
                </div>
                
                <!-- Interactive Quiz -->
                <div class="quiz-container" id="quizContainer">
                    <div class="quiz-progress">
                        <div class="progress-dot active" id="dot1"></div>
                        <div class="progress-dot" id="dot2"></div>
                        <div class="progress-dot" id="dot3"></div>
                    </div>
                    
                    <div class="quiz-question" id="quizQuestion">
                        What is 7 + 8?
                    </div>
                    <div class="quiz-options" id="quizOptions">
                        <div class="quiz-option" data-answer="false" onclick="selectAnswer(this)">13</div>
                        <div class="quiz-option" data-answer="true" onclick="selectAnswer(this)">15</div>
                        <div class="quiz-option" data-answer="false" onclick="selectAnswer(this)">16</div>
                        <div class="quiz-option" data-answer="false" onclick="selectAnswer(this)">14</div>
                    </div>
                    <button class="quiz-btn" id="nextBtn" onclick="nextQuestion()" disabled>
                        <span style="position: relative; z-index: 2;">Next Question →</span>
                    </button>
                </div>
                
                <!-- Quiz Results -->
                <div class="quiz-results" id="quizResults">
                    <div class="result-badge">🎓 Quiz Completed</div>
                    <h3 class="result-message">🎉 Excellent Work! 🎉</h3>
                    <div class="result-score" id="resultScore">100%</div>
                    <div class="result-stars">
                        <span class="star">⭐</span>
                        <span class="star">⭐</span>
                        <span class="star">⭐</span>
                    </div>
                    <div class="result-details">
                        <div class="result-stat">
                            <div class="result-stat-value" id="correctCount">3</div>
                            <div class="result-stat-label">Correct</div>
                        </div>
                        <div class="result-stat">
                            <div class="result-stat-value">3</div>
                            <div class="result-stat-label">Total</div>
                        </div>
                    </div>
                    <p style="color: #2d3748; font-size: 1.1rem; margin: 18px 0; font-weight: 600;">
                        ⚡ Auto-graded instantly!<br>
                        <span style="color: #4CAF50;">This is the power of Navuli Fiji.</span>
                    </p>
                    <button class="quiz-btn" onclick="restartQuiz()" style="background: var(--gradient-1);">
                        <span style="position: relative; z-index: 2;"><i class="fas fa-redo"></i> Try Again</span>
                    </button>
                </div>
            </div>
            
            <!-- Right: Launch Information Card -->
            <div class="card">
                <div class="card-accent gold"></div>
                <h2 class="card-title" style="background: var(--gradient-2); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    🚀 Launching Soon
                </h2>
                <p class="card-subtitle">The future of Pacific education starts here</p>
                
                <p style="font-size: 1.12rem; color: #555; text-align: center; margin-bottom: 30px; line-height: 1.8; font-weight: 500;">
                    Revolutionary school management designed for Fiji's educational excellence. 
                    Smart, efficient, and built for the Pacific.
                </p>
                
                <!-- Countdown -->
                <div class="countdown">
                    <div class="countdown-item">
                        <span class="countdown-value" id="days">00</span>
                        <span class="countdown-label">Days</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-value" id="hours">00</span>
                        <span class="countdown-label">Hours</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-value" id="minutes">00</span>
                        <span class="countdown-label">Minutes</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-value" id="seconds">00</span>
                        <span class="countdown-label">Seconds</span>
                    </div>
                </div>
                
                <!-- Features -->
                <div class="features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-brain"></i>
                        </div>
                        <div class="feature-content">
                            <h4>AI-Powered Learning</h4>
                            <p>Intelligent assessments adapting to each student's unique pace</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="feature-content">
                            <h4>Real-Time Analytics</h4>
                            <p>Instant insights with predictive performance tracking</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div class="feature-content">
                            <h4>Mobile-First Design</h4>
                            <p>Seamless access anywhere with offline capability</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="feature-content">
                            <h4>Bank-Level Security</h4>
                            <p>Military-grade encryption protecting sensitive data</p>
                        </div>
                    </div>
                </div>
                
                <!-- Email Form -->
                <div class="email-form">
                    <h3 class="form-title">
                        <i class="fas fa-bell"></i> Get Notified at Launch
                    </h3>
                    <form id="emailForm">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <input 
                                type="email" 
                                class="form-input" 
                                placeholder="Enter your email address"
                                required
                                id="emailInput"
                                name="email"
                            >
                            <button type="submit" class="form-btn" id="submitBtn">
                                <span style="position: relative; z-index: 2;">
                                    <i class="fas fa-paper-plane"></i> Notify Me
                                </span>
                            </button>
                        </div>
                    </form>
                    <div class="success-msg" id="successMsg">
                        <i class="fas fa-check-circle"></i> 
                        <span id="successMsgText">Thank you! We'll notify you when we launch.</span>
                    </div>
                    <div class="error-msg" id="errorMsg" style="display: none; padding: 16px 22px; background: linear-gradient(135deg, #f44336, #d32f2f); color: white; border-radius: 14px; margin-top: 18px; font-weight: 600; box-shadow: 0 6px 22px rgba(244, 67, 54, 0.3);">
                        <i class="fas fa-exclamation-circle"></i> 
                        <span id="errorMsgText">An error occurred. Please try again.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Compact Footer -->
    <div class="footer">
        <div class="social-links">
            <a href="https://facebook.com/navuliFiji" title="Facebook" aria-label="Facebook" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://twitter.com/navuliFiji" title="Twitter" aria-label="Twitter" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-twitter"></i>
            </a>
            <a href="https://linkedin.com/company/navuliFiji" title="LinkedIn" aria-label="LinkedIn" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-linkedin-in"></i>
            </a>
            <a href="https://instagram.com/navuliFiji" title="Instagram" aria-label="Instagram" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://youtube.com/navuliFiji" title="YouTube" aria-label="YouTube" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-youtube"></i>
            </a>
        </div>
        
        <p style="font-size: 0.95rem; font-weight: 500; margin-bottom: 8px;">
            &copy; <?= date('Y') ?> Navuli Fiji | Empowering Pacific Education
        </p>
        
        <p style="font-size: 0.85rem; margin-top: 10px; opacity: 0.9; line-height: 1.6;">
            <i class="fas fa-map-marker-alt"></i> Proudly Built for Fiji 🇫🇯
        </p>
        
        <p style="font-size: 0.85rem; margin-top: 8px; opacity: 0.9;">
            Developed by: 
            <a href="https://baleicoqe.com" target="_blank" rel="noopener noreferrer" class="footer-link">
                <i class="fas fa-code"></i> Pio Baleicoqe
            </a>
        </p>
    </div>

    
    <style>
/* Footer Link Styling */
.footer-link {
    color: #cce7ff;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    border-bottom: 1px solid transparent;
    padding-bottom: 2px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.footer-link:hover {
    color: #FFD700;
    border-bottom-color: #FFD700;
    text-shadow: 0 0 15px rgba(255, 215, 0, 0.5);
    transform: translateY(-2px);
}

.footer-link:active {
    color: #FFA500;
    transform: translateY(0);
}

.footer-link i {
    font-size: 0.9em;
}

/* Responsive footer */
@media (max-width: 480px) {
    .footer p {
        font-size: 0.8rem !important;
    }
    
    .social-links a {
        width: 42px;
        height: 42px;
        font-size: 1.1rem;
        margin: 0 6px;
    }
}
</style>
    
    <!-- Scroll to Top Button -->
    <div class="scroll-to-top" id="scrollToTop" onclick="scrollToTop()">
        <i class="fas fa-arrow-up"></i>
    </div>
    
    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <i class="fas fa-info-circle toast-icon"></i>
        <span class="toast-message" id="toastMessage"></span>
    </div>
    
    <script>
        // Minimal Particle Generator (Reduced from 40 to 20)
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            for (let i = 0; i < 20; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 20 + 's';
                particle.style.animationDuration = (Math.random() * 15 + 15) + 's';
                particlesContainer.appendChild(particle);
            }
        }
        
        createParticles();
        
        // Typing Animation
        const codeLines = [
            'Loading test...',
            'Question 1/3',
            '> 7 + 8 = ?',
            'Answer: 15 ✓',
            'Processing...',
            'Score: 33%',
            '',
            'Next question...'
        ];
        
        let lineIndex = 0;
        let charIndex = 0;
        let currentLine = '';
        
        function typeCode() {
            if (lineIndex < codeLines.length) {
                if (charIndex < codeLines[lineIndex].length) {
                    currentLine += codeLines[lineIndex][charIndex];
                    document.getElementById('typingText').innerHTML = currentLine;
                    charIndex++;
                    setTimeout(typeCode, 70);
                } else {
                    currentLine += '<br>';
                    lineIndex++;
                    charIndex = 0;
                    setTimeout(typeCode, 700);
                }
            } else {
                setTimeout(() => {
                    lineIndex = 0;
                    charIndex = 0;
                    currentLine = '';
                    document.getElementById('typingText').innerHTML = '';
                    typeCode();
                }, 2500);
            }
        }
        
        typeCode();
        
        // Quiz Logic
        const quizData = [
            {
                question: "What is 7 + 8?",
                options: ["13", "15", "16", "14"],
                correct: 1
            },
            {
                question: "What is 12 × 3?",
                options: ["32", "36", "34", "38"],
                correct: 1
            },
            {
                question: "What is 25 ÷ 5?",
                options: ["4", "5", "6", "7"],
                correct: 1
            }
        ];
        
        let currentQuestion = 0;
        let score = 0;
        let selectedOption = null;
        
        function updateProgressDots() {
            for (let i = 1; i <= 3; i++) {
                const dot = document.getElementById(`dot${i}`);
                dot.classList.remove('active', 'completed');
                
                if (i < currentQuestion + 1) {
                    dot.classList.add('completed');
                } else if (i === currentQuestion + 1) {
                    dot.classList.add('active');
                }
            }
        }
        
        function selectAnswer(element) {
            document.querySelectorAll('.quiz-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            
            element.classList.add('selected');
            selectedOption = element;
            document.getElementById('nextBtn').disabled = false;
        }
        
        function nextQuestion() {
            if (!selectedOption) return;
            
            const isCorrect = selectedOption.dataset.answer === 'true';
            
            if (isCorrect) {
                selectedOption.classList.add('correct');
                score++;
            } else {
                selectedOption.classList.add('incorrect');
                document.querySelectorAll('.quiz-option').forEach(opt => {
                    if (opt.dataset.answer === 'true') {
                        opt.classList.add('correct');
                    }
                });
            }
            
            document.querySelectorAll('.quiz-option').forEach(opt => {
                opt.style.pointerEvents = 'none';
            });
            
            document.getElementById('nextBtn').disabled = true;
            
            setTimeout(() => {
                currentQuestion++;
                
                if (currentQuestion < quizData.length) {
                    loadQuestion();
                } else {
                    showResults();
                }
            }, 1600);
        }
        
        function loadQuestion() {
            const q = quizData[currentQuestion];
            
            selectedOption = null;
            document.getElementById('nextBtn').disabled = true;
            
            updateProgressDots();
            
            const questionEl = document.getElementById('quizQuestion');
            questionEl.style.opacity = '0';
            questionEl.style.transform = 'translateY(-15px)';
            
            setTimeout(() => {
                questionEl.textContent = q.question;
                questionEl.style.transition = 'all 0.3s ease';
                questionEl.style.opacity = '1';
                questionEl.style.transform = 'translateY(0)';
            }, 250);
            
            const optionsContainer = document.getElementById('quizOptions');
            optionsContainer.innerHTML = '';
            
            q.options.forEach((option, index) => {
                setTimeout(() => {
                    const div = document.createElement('div');
                    div.className = 'quiz-option';
                    div.textContent = option;
                    div.dataset.answer = index === q.correct ? 'true' : 'false';
                    div.onclick = function() { selectAnswer(this); };
                    div.style.opacity = '0';
                    div.style.transform = 'translateX(-25px)';
                    optionsContainer.appendChild(div);
                    
                    setTimeout(() => {
                        div.style.transition = 'all 0.35s ease';
                        div.style.opacity = '1';
                        div.style.transform = 'translateX(0)';
                    }, 40);
                }, index * 90);
            });
        }
        
        function showResults() {
            const percentage = Math.round((score / quizData.length) * 100);
            
            const quizContainer = document.getElementById('quizContainer');
            quizContainer.style.transition = 'all 0.4s ease';
            quizContainer.style.opacity = '0';
            quizContainer.style.transform = 'scale(0.95)';
            
            setTimeout(() => {
                quizContainer.style.display = 'none';
                
                const resultsEl = document.getElementById('quizResults');
                resultsEl.classList.add('show');
                resultsEl.style.opacity = '0';
                resultsEl.style.transform = 'scale(0.9)';
                
                setTimeout(() => {
                    resultsEl.style.transition = 'all 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
                    resultsEl.style.opacity = '1';
                    resultsEl.style.transform = 'scale(1)';
                }, 40);
                
                document.getElementById('resultScore').textContent = percentage + '%';
                document.getElementById('correctCount').textContent = score;
                
                const resultMessage = document.querySelector('.result-message');
                if (percentage === 100) {
                    resultMessage.innerHTML = '🎉 Perfect Score! 🎉';
                } else if (percentage >= 66) {
                    resultMessage.innerHTML = '👏 Great Job! 👏';
                } else {
                    resultMessage.innerHTML = '👍 Good Effort! 👍';
                }
                
                createConfetti();
            }, 350);
        }
        
        function createConfetti() {
            const colors = ['#FFD700', '#FFA500', '#FF69B4', '#00CED1', '#9370DB', '#32CD32'];
            const resultsDiv = document.getElementById('quizResults');
            
            for (let i = 0; i < 50; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.style.position = 'absolute';
                    confetti.style.width = '8px';
                    confetti.style.height = '8px';
                    confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.left = Math.random() * 100 + '%';
                    confetti.style.top = '-10px';
                    confetti.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';
                    confetti.style.opacity = '1';
                    confetti.style.zIndex = '1000';
                    
                    resultsDiv.appendChild(confetti);
                    
                    let position = -10;
                    let angle = 0;
                    const fallSpeed = 2 + Math.random() * 2.5;
                    const xMovement = (Math.random() - 0.5) * 150;
                    
                    const fall = setInterval(() => {
                        position += fallSpeed;
                        angle += 8;
                        confetti.style.top = position + 'px';
                        confetti.style.transform = `translateX(${xMovement * (position / 450)}px) rotate(${angle}deg)`;
                        confetti.style.opacity = 1 - (position / 450);
                        
                        if (position > 450) {
                            clearInterval(fall);
                            confetti.remove();
                        }
                    }, 18);
                }, i * 25);
            }
        }
        
        function restartQuiz() {
            currentQuestion = 0;
            score = 0;
            
            const resultsEl = document.getElementById('quizResults');
            resultsEl.style.opacity = '0';
            resultsEl.style.transform = 'scale(0.9)';
            
            setTimeout(() => {
                resultsEl.classList.remove('show');
                
                const quizContainer = document.getElementById('quizContainer');
                quizContainer.style.display = 'block';
                quizContainer.style.opacity = '0';
                quizContainer.style.transform = 'scale(0.95)';
                
                setTimeout(() => {
                    quizContainer.style.transition = 'all 0.4s ease';
                    quizContainer.style.opacity = '1';
                    quizContainer.style.transform = 'scale(1)';
                }, 40);
                
                loadQuestion();
            }, 350);
        }
        
        // Countdown Timer
        const launchDate = new Date('2025-06-01T00:00:00').getTime();
        
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = launchDate - now;
            
            if (distance < 0) {
                document.querySelector('.countdown').innerHTML = `
                    <div style="grid-column: 1 / -1; padding: 35px; background: var(--gradient-2); border-radius: 18px; color: #003366; box-shadow: 0 10px 35px rgba(255, 215, 0, 0.4);">
                        <h2 style="font-size: 2.8rem; margin: 0;">🎉 We're Live! 🎉</h2>
                    </div>
                `;
                return;
            }
            
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            document.getElementById('days').textContent = String(days).padStart(2, '0');
            document.getElementById('hours').textContent = String(hours).padStart(2, '0');
            document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
            document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
        }
        
        updateCountdown();
        setInterval(updateCountdown, 1000);
        
        function submitEmail(e) {
    e.preventDefault();
    
    const emailInput = document.getElementById('emailInput');
    const email = emailInput.value.trim();
    const submitBtn = document.getElementById('submitBtn');
    const successMsg = document.getElementById('successMsg');
    const errorMsg = document.getElementById('errorMsg');
    
    console.log('📧 Submitting email:', email);
    
    // Hide previous messages
    successMsg.classList.remove('show');
    errorMsg.style.display = 'none';
    
    // Validate email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        console.error('❌ Invalid email format');
        showToast('Please enter a valid email address', 'error');
        emailInput.style.borderColor = '#f44336';
        return;
    }
    
    // Disable form
    submitBtn.disabled = true;
    emailInput.disabled = true;
    submitBtn.innerHTML = '<span style="position: relative; z-index: 2;"><i class="fas fa-spinner fa-spin"></i> Sending...</span>';
    
    showLoading();
    
    // Create simple form data
    const formData = new URLSearchParams();
    formData.append('email', email);
    
    console.log('🚀 Sending request to:', '<?= base_url('launch/subscribe') ?>');
    
    // Send request
    fetch('<?= base_url('launch/subscribe') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: formData.toString()
    })
    .then(response => {
        console.log('📥 Response status:', response.status);
        return response.json().then(data => ({
            status: response.status,
            data: data
        }));
    })
    .then(({status, data}) => {
        console.log('📊 Response data:', data);
        hideLoading();
        
        if (status === 201) {
            // Success
            console.log('✅ Success!');
            successMsg.querySelector('#successMsgText').textContent = data.message;
            successMsg.classList.add('show');
            showToast(data.message, 'success');
            emailInput.value = '';
            createEmailConfetti();
            
            setTimeout(() => {
                successMsg.style.opacity = '0';
                setTimeout(() => {
                    successMsg.classList.remove('show');
                    successMsg.style.opacity = '1';
                }, 300);
            }, 8000);
            
        } else if (status === 409) {
            // Already registered
            console.log('⚠️ Email already registered');
            errorMsg.querySelector('#errorMsgText').textContent = data.message;
            errorMsg.style.display = 'flex';
            errorMsg.style.alignItems = 'center';
            errorMsg.style.justifyContent = 'center';
            errorMsg.style.gap = '10px';
            errorMsg.style.background = 'linear-gradient(135deg, #FFA500, #FF8C00)';
            showToast(data.message, 'error');
            emailInput.value = '';
            
            setTimeout(() => {
                errorMsg.style.opacity = '0';
                setTimeout(() => {
                    errorMsg.style.display = 'none';
                    errorMsg.style.opacity = '1';
                    errorMsg.style.background = 'linear-gradient(135deg, #f44336, #d32f2f)';
                }, 300);
            }, 8000);
            
        } else {
            // Other errors
            console.error('❌ Error:', data);
            errorMsg.querySelector('#errorMsgText').textContent = data.message || 'An error occurred.';
            errorMsg.style.display = 'flex';
            errorMsg.style.alignItems = 'center';
            errorMsg.style.justifyContent = 'center';
            errorMsg.style.gap = '10px';
            showToast(data.message || 'An error occurred.', 'error');
            
            setTimeout(() => {
                errorMsg.style.opacity = '0';
                setTimeout(() => {
                    errorMsg.style.display = 'none';
                    errorMsg.style.opacity = '1';
                }, 300);
            }, 6000);
        }
    })
    .catch(error => {
        console.error('💥 Fetch error:', error);
        hideLoading();
        
        errorMsg.querySelector('#errorMsgText').textContent = 'Network error. Please try again.';
        errorMsg.style.display = 'flex';
        errorMsg.style.alignItems = 'center';
        errorMsg.style.justifyContent = 'center';
        errorMsg.style.gap = '10px';
        showToast('Network error. Please try again.', 'error');
        
        setTimeout(() => {
            errorMsg.style.opacity = '0';
            setTimeout(() => {
                errorMsg.style.display = 'none';
                errorMsg.style.opacity = '1';
            }, 300);
        }, 6000);
    })
    .finally(() => {
        submitBtn.disabled = false;
        emailInput.disabled = false;
        emailInput.style.borderColor = '#e0e0e0';
        submitBtn.innerHTML = '<span style="position: relative; z-index: 2;"><i class="fas fa-paper-plane"></i> Notify Me</span>';
    });
}

// Attach event listener
document.addEventListener('DOMContentLoaded', function() {
    const emailForm = document.getElementById('emailForm');
    if (emailForm) {
        emailForm.addEventListener('submit', submitEmail);
        console.log('✅ Email form listener attached');
    } else {
        console.error('❌ Email form not found!');
    }
});

        // Confetti effect specifically for email subscription
        function createEmailConfetti() {
            const colors = ['#FFD700', '#FFA500', '#00CED1', '#32CD32', '#FF69B4'];
            const emailForm = document.querySelector('.email-form');
            
            for (let i = 0; i < 30; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.style.position = 'fixed';
                    confetti.style.width = '10px';
                    confetti.style.height = '10px';
                    confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';
                    confetti.style.zIndex = '10000';
                    confetti.style.pointerEvents = 'none';
                    
                    const rect = emailForm.getBoundingClientRect();
                    confetti.style.left = (rect.left + rect.width / 2) + 'px';
                    confetti.style.top = (rect.top + rect.height / 2) + 'px';
                    
                    document.body.appendChild(confetti);
                    
                    const angle = (Math.random() * 360) * (Math.PI / 180);
                    const velocity = 3 + Math.random() * 5;
                    let x = 0;
                    let y = 0;
                    let rotation = 0;
                    let opacity = 1;
                    
                    const animate = () => {
                        x += Math.cos(angle) * velocity;
                        y += Math.sin(angle) * velocity + (velocity * 0.5); // Gravity effect
                        rotation += 10;
                        opacity -= 0.02;
                        
                        confetti.style.transform = `translate(${x}px, ${y}px) rotate(${rotation}deg)`;
                        confetti.style.opacity = opacity;
                        
                        if (opacity > 0) {
                            requestAnimationFrame(animate);
                        } else {
                            confetti.remove();
                        }
                    };
                    
                    animate();
                }, i * 20);
            }
        }
        
        // Add this to your existing form submission listener
        document.getElementById('emailForm').addEventListener('submit', submitEmail);
        
        // Toast Notification System
        function showToast(message, type = 'info') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            const toastIcon = toast.querySelector('.toast-icon');
            
            toast.classList.remove('success', 'error');
            if (type !== 'info') {
                toast.classList.add(type);
            }
            
            if (type === 'success') {
                toastIcon.className = 'fas fa-check-circle toast-icon';
                toastIcon.style.color = '#4CAF50';
            } else if (type === 'error') {
                toastIcon.className = 'fas fa-exclamation-circle toast-icon';
                toastIcon.style.color = '#f44336';
            } else {
                toastIcon.className = 'fas fa-info-circle toast-icon';
                toastIcon.style.color = '#0066cc';
            }
            
            toastMessage.textContent = message;
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 4000);
        }
        
        // Loading Indicator
        function showLoading() {
            const loader = document.getElementById('loadingIndicator');
            loader.style.transform = 'scaleX(0.7)';
            
            setTimeout(() => {
                loader.style.transform = 'scaleX(1)';
            }, 100);
        }
        
        function hideLoading() {
            const loader = document.getElementById('loadingIndicator');
            loader.style.transform = 'scaleX(0)';
        }
        
        // Scroll to Top Functionality
        window.addEventListener('scroll', function() {
            const scrollBtn = document.getElementById('scrollToTop');
            if (window.pageYOffset > 300) {
                scrollBtn.classList.add('show');
            } else {
                scrollBtn.classList.remove('show');
            }
        });
        
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
        
        // Smooth Scroll for Anchor Links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Parallax Effect (Minimal)
        let ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    const scrolled = window.pageYOffset;
                    const backgroundMinimal = document.querySelector('.background-minimal');
                    
                    if (backgroundMinimal) {
                        backgroundMinimal.style.transform = 'translateY(' + scrolled * 0.2 + 'px)';
                    }
                    
                    ticking = false;
                });
                
                ticking = true;
            }
        });
        
        // Intersection Observer for Entrance Animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -80px 0px'
        };
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(25px)';
            card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
            observer.observe(card);
        });
        
        // Performance: Disable animations on slower devices
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
        if (prefersReducedMotion.matches) {
            document.querySelectorAll('*').forEach(el => {
                el.style.animation = 'none';
                el.style.transition = 'none';
            });
        }
        
        // Initialize on Load
        window.addEventListener('load', function() {
            document.querySelectorAll('.card').forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 150);
            });
            
            // Welcome Toast
            setTimeout(() => {
                showToast('Welcome to Navuli Fiji! 🌴', 'info');
            }, 1500);
        });
        
        // Add Touch Support for Mobile
        if ('ontouchstart' in window) {
            document.querySelectorAll('.quiz-option, .form-btn, .quiz-btn, .feature-item').forEach(element => {
                element.addEventListener('touchstart', function() {
                    this.style.transform = 'scale(0.98)';
                });
                
                element.addEventListener('touchend', function() {
                    this.style.transform = '';
                });
            });
        }
        
        // Console Branding
        console.log('%c🌴 Navuli Fiji - Coming Soon! 🇫🇯', 'color: #0066cc; font-size: 22px; font-weight: bold; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);');
        console.log('%cWelcome to the future of Pacific education!', 'color: #FFD700; font-size: 16px; font-weight: 600;');
        console.log('%cBuilt with ❤️ for Fiji', 'color: #4CAF50; font-size: 14px;');
    </script>
</body>
</html>