-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2024 at 06:18 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `online_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` char(4) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `attempts` int(11) DEFAULT 0,
  `last_attempt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `blocked_until` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_history`
--

CREATE TABLE `order_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` char(4) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `payment_option` varchar(50) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `datetime` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) DEFAULT NULL,
  `shipping_status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_history`
--

INSERT INTO `order_history` (`id`, `user_id`, `product_id`, `quantity`, `name`, `address`, `payment_option`, `payment_status`, `created_at`, `datetime`, `total`, `shipping_status`) VALUES
(50, 3, 'P101', 1, 'vincent', 'welocme 123 /', 'bank_transfer', 'Successful', '2024-09-24 15:18:28', '2024-09-24 15:18:28', 269.00, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` char(4) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(6,2) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `details` text NOT NULL,
  `status` enum('Active','Blocked') NOT NULL DEFAULT 'Active',
  `category` enum('Mouse','Keyboard','Headset','Speaker','Monitor') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `price`, `photo`, `details`, `status`, `category`) VALUES
('P001', 'Blackshark V2-Headset', 269.00, '66f2db4ebdfd9.jpg', 'Advanced Passive Noise Cancellation: sturdy closed earcups fully cover ears to prevent noise from leaking into the headset, with its cushions providing a closer seal for more sound isolation.\r\n\r\n\r\n\r\n7.1 Surround Sound for Positional Audio: Outfitted with custom-tuned 50 mm drivers, capable of software-enabled surround sound. *Only available on Windows 10 64bit\r\n\r\n\r\n\r\nTriforce Titanium 50mm High-End Sound Drivers: With titanium-coated diaphragms for added clarity, our new, cutting-edge proprietary design divides the driver into 3 parts for the individual tuning of highs, mids, and lows—producing brighter, clearer audio with richer highs and more powerful lows\r\n\r\n\r\n\r\nLightweight Design with Breathable Foam Ear Cushions: At just 240g, the BlackShark V2X is engineered from the ground up for maximum comfort\r\n\r\n\r\n\r\nHyperclear Cardioid Mic: Improved pickup pattern ensures more voice and less noise as it tapers off towards the mic’s back and sides\r\n\r\n\r\n\r\nCross-platform compatibility: Works with PC, Mac, PS4, Xbox One, Nintendo Switch via 3.5mm jack, enjoy unfair audio advantage across almost every platform.Xbox One stereo Adapter may be required, purchase separately', 'Active', 'Headset'),
('P002', 'G335-Headset', 269.00, '66f2dba42da20.jpg', 'Logitech G335 Wired Gaming Headset with Flip to Mute Microphone and 3.5 mm audio jack Take your gaming to the next level with Logitech G335 Wired Gaming Headset. These gaming headphones are lightweight and use a suspension headband design to help distribute weight and relieve pressure points. The elastic headband is adjustable and reversible for a customized look and fit. Soft memory foam ear cups and sports mesh material allow for long-lasting comfort, so you don\'t have to pause the game. G335 is easy to set up. Simply plug into your game with the 3.5 mm audio jack directly into your PC, laptop, gaming console/controller, or mobile device. The G335 gaming headset with mic is a versatile gaming companion. Easily control the volume or adjust the the microphone to fit all of your gaming needs. The colorful G335 headset is a smaller, lighter version of the award-winning G733 wireless gaming headset. G335 is even Discord Certified and available in a range of colors to fit your individual style. G335 Wired Gaming Headset is lightweight at only 240 g.', 'Active', 'Headset'),
('P003', 'Cloud Stinger 2-Headset', 199.00, '66f2dbdb4c9fe.jpg', 'Comfortable, Lightweight Gaming Audio Refined.\r\n\r\nWith a new design and 2 years of DTS Headphone:X Spatial Audio, the Cloud Stinger 2 keeps the fundamentals of the Cloud Stinger and refines it. Still weighing in at under 300g, the Cloud Stinger 2 is lightweight, but also still packs a hefty audio punch. Get a wide frequency response so you will not miss important audio cues that give your opponents away. It also does not skimp on comfort, with soft memory foam and premium leatherette designed for all-day gaming. Gamers will appreciate HyperX’s passion for its craft, which shows up in quality-of-life features like rotating earcups that make it easy to take a break, or the swivel-to-mute microphone that makes muting your mic simple and obvious. Its swivel-to-mute mic and volume controls are located on the headset, grouping all your most important audio functions right on your head for easy access. The passively noise-cancelling microphone is flexible, so you can precisely position it to give you clear communication with your team.', 'Active', 'Headset'),
('P004', 'G502-Mouse', 399.00, '66f2dc42eb4b7.jpg', 'Now you can game faster and more accurately, with G502 LIGHTSPEED featuring superfast 1 ms wireless connectivity. A next-gen HERO sensor delivers 25k DPI class-leading performance and energy efficiency—get up to 60 hours of uninterrupted gaming. 11 programmable buttons help you optimize gameplay with custom keybinds and macros. Primary buttons feature metal spring tensioning for fast and crisp actuation. Six adjustable weights let you find the right mouse feel. LIGHTSYNC RGB gives you ~16.8 million colors to create an exciting and immersive gaming environment. The hyper-fast scroll wheel lets you speed through long menus and documents.', 'Active', 'Mouse'),
('P005', 'G102-Mouse', 109.00, '66f2dd4a024fa.jpg', 'G102 LIGHTSYNC is ready to play with an 8,000 DPI sensor and customisable, vibrant LIGHTSYNC RGB. LIGHTSYNC RGB can be customised with colour wave effects or patterns across  ~16.8 million colors to suit your play style, setup, and mood. * A classic 6-button design gets you right into the game and can be programmed to simplify tasks.  G102 is designed to maximise the fun in your game.* *Advanced features require Logitech G HUB Gaming Software available at logitechG.com/GHUB', 'Active', 'Mouse'),
('P006', 'G304-Mouse', 245.00, '66f2dd85cc61d.jpg', 'G304 features the next-gen HERO sensor with 12,000 DPI sensitivity and LIGHTSPEED wireless 1 ms performance. It’s long-lasting with 250 gaming hours from one AA battery (an indicator light reminds you before you need a new AA), ultra-portable with built-in nano receiver storage, lightweight weighing in at 99 grams, and ready to game with 6 programmable buttons. G304 is the LIGHTSPEED wireless mouse for all.', 'Active', 'Mouse'),
('P007', 'G403-Mouse', 299.00, '66f2ddb5dc9a2.jpg', 'Logitech G403 HERO Gaming Mouse features the advanced next-gen HERO 25K sensor, with 1:1 tracking, 400+ IPS and 100-25,600 max DPI sensitivity – plus zero smoothing, filtering or acceleration. Full-spectrum RGB lighting responds to in-game action, audio and screen colour. Customise lighting effects from ~16.8M colours with G HUB gaming software, and sync across your G gear. G403 HERO is comfortably designed for gaming with a shape that’s easy to grip and control, using high-quality materials, lightweight construction (87g), rubber grips and a 10 g removable weight. Using G HUB, configure 6 programmable buttons to simplify in-game actions.', 'Active', 'Mouse'),
('P008', 'Cobra-Mouse', 340.00, '66f2ddeef0154.jpg', 'About Razer Cobra Wired Gaming Mouse\r\n\r\n58G LIGHTWEIGHT DESIGN — Built to fit most grip styles, the mouse allows for fast, precise control and feels comfortable to use during long gaming sessions\r\n\r\n\r\n\r\nGEN-3 OPTICAL MOUSE SWITCHES — Unrivalled durability and speed with switches that have an extended 90-million click lifecycle and eliminate double-clicking issues, boasting a blistering 0.2 ms actuation time without debounce delay\r\n\r\n\r\n\r\nCHROMA LIGHTING WITH GRADIENT UNDERGLOW — Customize the mouse with 16.8 million colors and countless lighting effects, creating a more immersive gaming experience as the lights react dynamically with hundreds of Chroma-integrated games\r\n\r\n\r\n\r\nPRECISE SENSOR ADJUSTMENTS — Fine-tune the mouse\'s sensitivity with precise adjustments in 50 DPI increments to achieve a truly tailored play style\r\n\r\n\r\n\r\nSPEEDFLEX CABLE — Provides greater flexibility and is designed to produce minimal drag, perform quicker and more fluid swipes, and offer a higher degree of control\r\n\r\n\r\n\r\n100% PTFE MOUSE FEET — Made from the highest grade of PTFE, the Cobra’s mouse feet on the front, rear, and sensor ring allows it to glide smoothly across any surface', 'Active', 'Mouse'),
('P009', 'Orochi V2-Mouse', 339.00, '66f2de31afaea.jpg', 'RAZER Orochi V2 Mobile Wireless Gaming Mouse (Black/White Edition)\r\n\r\n\r\n\r\n<60g Ultra-Lightweight Design: for seamless control when gaming on the go exlcudes battery weight\r\n\r\n\r\n\r\n2 Wireless Modes: Maximize its battery life for work via Bluetooth, and maximize your after-hours gaming with the seamless, low-latency performance of Razer HyperSpeed Wireless.\r\n\r\n\r\n\r\nUp to 950hrs of Battery Life: Built for the gaming and work grind, it lasts up to 950 hours on Bluetooth and up to 425 hours on Razer HyperSpeed Wireless, and can go for ages before its batteries need replacing.\r\n\r\n\r\n\r\n2nd-Gen Razer Mechanical Mouse Switches: With new gold-plated contact points, the switches are less prone to degrading and have a longer lifespan of up to 60 million clicks, so you can enjoy crisp execution that’s just as consistent\r\n\r\n\r\n\r\nRazer 5G Advanced 18K DPI Optical Sensor: Enjoy responsive, pixel-precise aim with an improved sensor that flawlessly tracks your movement with zero spinouts.\r\n\r\n\r\n\r\n1) RAZER Orochi V2 Mobile Wireless Gaming Mouse (Black/White Edition) x1\r\n\r\n2) Wireless USB Dongle x1\r\n\r\n3) AA Battery x1\r\n\r\n4) Product Information Guide x1', 'Active', 'Mouse'),
('P010', 'G PRO - Mouse', 549.00, '66f2de745e0c0.jpg', 'Designed over two years with direct input from many professional esports players, PRO Wireless gaming mouse is built to the exacting standards of some of the world’s top esports professionals. PRO Wireless gaming mouse is built for extreme performance and includes the latest and most advanced technologies available. Featuring LIGHTSPEED technology, PRO Wireless overcomes the limitations of latency, connectivity and power to provides rock-solid and super fast 1 ms report rate connection. PRO Wireless gaming mouse is also equipped with the latest version of the HERO sensor, our next generation optical sensor that is the highest performing and efficient gaming sensor.', 'Active', 'Mouse'),
('P011', 'F75-Keyboard', 336.00, '66f2ded0190d6.jpg', '● Product Name: AULA F75\r\n\r\n● Key count: 80 keys\r\n\r\n● Rated voltage: DC 3.7V (fully charged with 4.2V)\r\n\r\n● Battery capacity: 4000mAh rechargeable lithium battery\r\n\r\n● Product weight: approximately 1023g (including wire/receiver)\r\n\r\n● Transmission method: Bluetooth/2.4G/wired\r\n\r\n● Total travel of buttons: 4.0mm\r\n\r\n● Voltage/current: DC 5V ≌ 700mA\r\n\r\n● Charging interface: Type-C interface\r\n\r\n● Product size: 322.7 * 143.2 * 43.1 ± 1mm\r\n\r\n● Accessories: Key puller * 1, switch * 2, data cable * 1, instruction manual * 1', 'Active', 'Keyboard'),
('P012', 'RK M75-Keyboard', 450.00, '66f2df4f599c9.jpg', 'Prelubed stabilizers\r\nKailh Hot-swappable 5&3-pin switches support\r\nTri-mode wireless (Bluetooth, 2.4GHz, and cable)\r\nDetachable Type C cable\r\nDual Adjustable Angles with Non-Slip Rubber Feet\r\nCompatible with MAC and Windows System', 'Active', 'Keyboard'),
('P013', 'RK84-Keyboard', 360.00, '66f2df8558fda.jpg', 'Model: RK84\r\n\r\nKeys Layout: 84 keys\r\n\r\nSize: about 315*125*39mm\r\n\r\nBluetooth: Bluetooth 5.1\r\n\r\nSwitch: hot-swappable RK/cherry switch\r\n\r\nSpecifications: All keys conflict-free, hot-swappable\r\n\r\nBacklight: RGB backlight\r\n\r\nKeycap: Doubleshot keycaps\r\n\r\nBattery capacity: 3750 mAh lithium battery\r\n\r\nWeight: 0.9 kg\r\n\r\nConnection method: Bluetooth 5.1/2.4G/USB C wired\r\n\r\nData length: 1.8M\r\n\r\nReceiver: 2.4G wireless receiver', 'Active', 'Keyboard'),
('P014', 'M603-Keyboard', 769.00, '66f2dfc6989bc.jpg', 'ROG Falchion RX Low Profile\r\n\r\n\r\n\r\n ROG\r\n\r\n Falchion RX Low Profile 65% compact wireless gaming keyboard with ROG \r\n\r\nRX low-profile optical switches, tri-mode connection with ROG SpeedNova \r\n\r\nwireless technology and Omni Receiver, protective cover, integrated \r\n\r\nsilicone dampening foam, interactive touch panel, and MacOS support\r\n\r\n \r\n\r\n•Compact and slim design: 65% keyboard in a 60% frame with a 26.5 mm profile masterfully incorporates arrow and navigation keys\r\n\r\n\r\n\r\n\r\n\r\n•ROG RX Low-Profile Optical Switches: Pre-lubed RX \r\n\r\nRed and Blue switches feature centralized lighting and provide \r\n\r\nconsistent wobble-free keystrokes with near-zero debounce delay\r\n\r\n\r\n\r\n•Tri-mode connection: Connect via Bluetooth® (up to three devices simultaneously), 2.4 GHz with ROG SpeedNova wireless technology, or wired USB', 'Active', 'Keyboard'),
('P015', 'H81-Keyboard', 385.00, '66f2e02a1fe51.jpg', 'Gasket Structure - Higher-level keyboard structure,pure sound, soft feel, this wonderful feel can make the typing process very pleasant and full of fun.\r\n\r\n\r\n\r\n81 Keys, Redefine TKL - The innovative 80% unique layout differs from traditional TKL keyboards, which cuts more clumsy space off while supporting the same functionality as practical arrow, multimedia and control keys.\r\n\r\n\r\n\r\nHot-swappable - Hot-swap PCB allows you to replace 3 pins/5 pins switches freely without soldering issue. Enjoy the fun of making your own unique keyboard.\r\n\r\n\r\n\r\nRGB Backlit - 18 RGB backlight modes, 8 monochrome backlight modes and 1 full color backlight. These modes will give you more enjoyable when you gaming or typing at night.\r\n\r\n\r\n\r\nDIY Programmable - Software DIY light effect powerful game macros Full-key programmable', 'Active', 'Keyboard'),
('P016', 'SLINGSHOT 303-Keyboard', 139.00, '66f2e0c2b64b4.jpg', 'A25 IMPERION SLINGSHOT 303 2.4G+BT+WIRED MECHANICAL KEYBOARD\r\n\r\nIMPERION SLINGSHOT 303\r\n\r\n61 KEY BLUE SWITCH MECHANICAL KEYBOARD\r\n\r\nSUPPORT 2.4G, BLUETOOTH AND LINE\r\n\r\nBUILD IN 1450mAh BATTERY', 'Active', 'Keyboard'),
('P017', 'Psychfalconet-Keyboard', 189.00, '66f2e13e91a2d.jpg', 'Armaggeddon MKA-2C NEO Psychraven\r\n\r\nFeatures :\r\n- 87 Keys | Kevlartech Keyboard\r\n- 12 Backlight Effects\r\n- Hotswapable Modular Switch\r\n- 25 Keys Anti-Ghosting\r\n- Sleek Sporty Design\r\n- Angle Adjustable\r\n- KevlarTech high quality keycaps with lifetime fade proof warranty\r\n\r\nSpecifications :\r\n- 5-Zone Multyicolour LED Back Light\r\n- 25 Keys Anti-Ghosting\r\n- USB cable length : 1.5M\r\n- Connection : Wired\r\n- Lifespan of up to 20 Million keystrokes and cycles\r\n\r\nKeyboard Dimension and weight :\r\n- Dimension : Length : 358 x Width : 130. x Height : 34 MM\r\n- Weight : 620 Gram\r\n\r\nPackage Content :\r\n- MKA-2C NEO\r\n- User Manual\r\n- Key Cap Puller\r\n- Switch Puller\r\n- 2 x Addition Switch', 'Active', 'Keyboard'),
('P018', '24G4-Monitor', 699.00, '66f2e1a49602d.jpg', 'Only for the true valiant! This AOC 24G4 gaming monitor is made with a fast IPS panel, equipped with G-Sync Compatible technology, guaranteeing you unbeatable gaming experience with its true 0.5ms MPRT response time and 180 Hz refresh rate. The HDR10 feature captivates visual quality and raises your gaming performance to the next level! Enjoy an ultra-smooth gaming with this AOC gaming monitor to ensure you\'re always prepped to win!\r\nFast IPS1920 × 1080 (FHD)180Hz 0.5msG-Sync CompatibleHDR10', 'Active', 'Monitor'),
('P019', 'VA2432-H-Monitor', 399.00, '66f2e2129c90b.jpg', 'SuperClear® IPS panel\r\nFull HD 1080p resolution\r\n100Hz Variable Refresh Rate delivers fluid visuals\r\n1ms (MPRT) response time for precision\r\nBuilt-in Low Blue Light Screen for comfortable viewing', 'Active', 'Monitor'),
('P020', 'G255F-Monitor', 699.00, '66f2e245006ad.jpg', '======================\r\nHighlights\r\n======================\r\n\r\n24.5\" FHD 1920 x 1080 Flat\r\n180Hz Refresh Rate\r\n1ms (GtG) Response Time\r\n1x DisplayPort (1.2a), 2x HDMI (2.0b), 1x Headphone-out\r\nAdaptive-sync Technology\r\n3 Years Warranty by MSI Malaysia\r\n\r\n\r\n======================\r\nSpecification\r\n======================\r\n[Technology]: Adaptive-sync Technology\r\n[Brightness]: 300 Nits\r\n[Contrast Ratio]: 1000:1\r\n[Response Time]: 1ms (GtG)\r\n[Refresh Rate]: 180Hz\r\n[Display Screen / Design / Resolution]: 24.5\" FHD 1920 x 1080 Flat @IPS\r\n\r\n[Interface]: 2x HDMI (2.0b)\r\n1x DP (1.2a)\r\n1x Earphone-out\r\n[Dimensions]: 557.1 x 220.1 x 418.5 mm\r\n557.1 x 66.4 x 326.9 mm (without stand)\r\n[Weight]: 5.13 kg\r\n2.87 kg (without stand)', 'Active', 'Monitor'),
('P021', 'VX2479-Monitor', 599.00, '66f2e29b909b7.jpg', 'Panel Type: IPS Technology\r\n\r\nResolution: 1920 x 1080\r\n\r\nResolution Type: FHD (Full HD)\r\n\r\nStatic Contrast Ratio: 1,000:1 (typ)\r\n\r\nDynamic Contrast Ratio: 80M: 1\r\n\r\nLight source: LED\r\n\r\nBrightness: 250 cd/m² (typ)\r\n\r\nColors: 16.7M\r\n\r\nColor Space Support: 8 bit (6 bit + FRC)\r\n\r\nAspect Ratio: 16:9\r\n\r\nResponse Time (MPRT): 0.5ms\r\n\r\nViewing Angle: 178º horizontal, 178º vertical\r\n\r\nBacklight Life (Hours): 30000 Hrs (Min)\r\n\r\nBend: Flat\r\n\r\nRefresh Rate (Hz): 165\r\n\r\nVariable Refresh Rate Technology: FreeSync Premium, AdaptiveSync\r\n\r\nFilter Cahaya Biru: Yes\r\n\r\nFlicker-Free: Yes\r\n\r\nColor Gamut: NTSC: 72% size (Typ)\r\n\r\nsRGB: 104% size (Typ)\r\n\r\nUkuran Piksel: 0.275 mm (H) x 0.275 mm (V)\r\n\r\nSurface Treatment: Anti-Glare, Hard Coating (3H)\r\n\r\n\r\n\r\nResolusi PC (max): 1920x1080\r\n\r\nResolusi Mac® (maks): 1920x1080\r\n\r\nSistem Operasi PC: Windows 10/11 certified; macOS tested\r\n\r\nResolusi Mac® (min): 1920x1080', 'Active', 'Monitor'),
('P022', 'VG259Q3A-Monitor', 749.00, '66f2e2f493886.jpg', 'Panel Size (inch) : 24.5\r\n\r\nAspect Ratio : 16:9\r\n\r\nDisplay Viewing Area (H x V) : 543.74 x 302.62 mm\r\n\r\nDisplay Surface : Non-Glare\r\n\r\nBacklight Type : LED\r\n\r\nPanel Type : IPS\r\n\r\nViewing Angle (CR≧10, H/V) : 178°/ 178°\r\n\r\nPixel Pitch : 0.2832mm x 0.2802mm\r\n\r\nResolution : 1920x1080\r\n\r\nColor Space (sRGB) : 99%\r\n\r\nBrightness (Typ.) : 250cd/㎡\r\n\r\nContrast Ratio (Typ.) : 1000:1\r\n\r\nDisplay Colors : 16.7M\r\n\r\nResponse Time : 1ms(GTG)\r\n\r\nRefresh Rate (Max) : 180Hz\r\n\r\nFlicker-free : Yes\r\n\r\n\r\n\r\nVideo Feature : \r\n\r\nTrace Free Technology : Yes\r\n\r\nGameVisual : Yes\r\n\r\nColor Temp. Selection : Yes(4 modes)\r\n\r\nGamePlus : Yes\r\n\r\nHDCP : Yes, 2.2\r\n\r\nExtreme Low Motion Blur : Yes\r\n\r\nVRR Technology : Yes (Adaptive-Sync)\r\n\r\nGameFast Input technology : Yes\r\n\r\nShadow Boost : Yes\r\n\r\nDisplayWidget : Yes, DisplayWidget Center\r\n\r\nLow Blue Light : Yes\r\n\r\n\r\n\r\nAudio Feature : \r\n\r\nSpeaker : Yes(2Wx2)\r\n\r\n\r\n\r\nI/O Ports\r\n\r\nDisplayPort 1.2 x 1\r\n\r\nHDMI(v2.0) x 2\r\n\r\nEarphone Jack : Yes', 'Active', 'Monitor'),
('P023', '27KGM3-Monitor', 799.00, '66f2e321b76c0.jpg', 'Model : 27KG3 M3\r\n\r\nSIZE : 27\"\r\n\r\nPANEL : IPS\r\n\r\nMAX RATE :1920 x 1080   (FHD)\r\n\r\nRefresh Rate : 180HZ\r\n\r\nContrast : 100mil:1  (MAX)\r\n\r\nResponse Time :0.5MS(GTG)\r\n\r\nViewing Angle : 178° (H) / 178°(V)\r\n\r\nBrightness: 250nits\r\n\r\nInput: HDMI + DP\r\n\r\nVESA Mount : Yes (75x75)\r\n\r\nSpeaker : NA\r\n\r\nTearing Prevention Technology  : AMD FREESYNC\r\n\r\nWarranty : 3-Years Warranty', 'Active', 'Monitor'),
('P024', 'NITRO XV240Y-Monitor', 649.00, '66f2e36c8c175.jpg', '======================\r\nHighlights\r\n======================\r\n\r\n23.8\" FHD (1920 x 1080) IPS\r\nRefresh Rate : 100Hz (FreeSync)\r\nResponse Time : 1ms VRB\r\nBrightness : 250 (cd/m2)\r\nInput : HDMI / DP\r\n3 Years Acer Malaysia Warranty\r\n\r\n\r\n======================\r\nSpecification\r\n======================\r\n[Technology]: AMD FreeSync\r\n[Brightness]: 250 (cd/m2)\r\n\r\n[Response Time]: 1ms VRB\r\n\r\n[Refresh Rate]: 100Hz\r\n[Display Screen / Design / Resolution]: 23.8\" FHD (1920 x 1080) IPS\r\n[Interface]: 1x HDMI,\r\n1x DP \r\n[Dimensions]: 36.7 cm x 61.4 cm x 6.6 cm\r\n[Weight]: 4.97 kg\r\n[Remark]: 3 Years Acer Malaysia Warranty', 'Active', 'Monitor'),
('P025', 'MARSHALL EMBERTON III-Speaker', 999.00, '66f2e42e1f193.jpg', '●Emberton III features Marshall signature sound with Dynamic Loudness that adjusts the tonal balance of the audio to ensure your music sounds brilliant at every volume.\r\n\r\n● Emberton III features True Stereophonic, a unique form of multi-directional sound from Marshall. Experience superior spatial and binaural sound that flows around you and fills any space.\r\n\r\n● Marshall’s on the road spirit lives on in our Emberton III speaker, boasting a rugged design that can battle dust and rain.\r\n\r\n●  Music comes first, but we know life gets busy. Emberton III now comes with a built-in microphone, making hands-free talking much simpler and clearer.\r\n\r\n● Emberton III is built to be Bluetooth LE Audio-ready. This future-proof technology will open a new world of audio sharing possibilities with AuracastTM.', 'Active', 'Speaker'),
('P026', 'Razer Leviathan V2', 509.00, '66f2e5175b57d.jpg', 'PC Soundbar with Full-Range Drivers: Armed with two full-range drivers and two passive radiators, the Leviathan V2 X delivers a riveting audio experience across all your entertainment.Waterproof : No\r\n\r\n\r\n\r\nCompact Desktop Form Factor: The most compact soundbar in our Leviathan V2 range fits perfectly beneath your monitor for a clutter-free desktop\r\n\r\n\r\n\r\nUSB Type C Power and Audio Delivery: Powered by a single USB Type C cable to deliver dynamic audio with a volume output of up to 90Db and enables for an easy setup\r\n\r\n\r\n\r\nBluetooth 5.0: Enjoy smooth, stutter-free sound with a low-latency Bluetooth connection, as you switch seamlessly between your PC and your preferred mobile device that’s conveniently paired via the Razer Audio App\r\n\r\n\r\n\r\nPowered by Razer Chroma RGB: With 14 lighting zones, countless patterns, dynamic in-game lighting effects—experience full RGB customization and deeper immersion with the world’s largest lighting ecosystem for gaming devices\r\n\r\n\r\n\r\nRazer Audio App and Razer Synapse: From customizing RGB lighting to toggling between audio devices, tweak the soundbar to best suit your needs with software designed to give you more control', 'Active', 'Speaker'),
('P027', 'g2000-Speaker', 419.00, '66f2e583afb70.jpg', 'Small But Powerful\r\n\r\nHIFI level 2.75 inch full-range unit with 16W RMS power output. \r\n\r\n\r\n\r\nMega Bass Port\r\n\r\nColumn-shaped cabinet with backward mega bass reflex port to reinforce overall bass output.\r\n\r\n\r\n\r\nRGB LED Accent\r\n\r\n12 light effects enhance gaming experience.\r\n\r\n\r\n\r\nFinely Crafted\r\n\r\nBuilt with high quality parts. Feel the detail of superb craftmanship.\r\n\r\n\r\n\r\nEasy Control\r\n\r\nMechanical shift lever easy to control volume. Also feature convenient buttons to switch RGB/sound mode and connect bluetooth.\r\n\r\nTop Button:\r\n\r\n- Long Press: On/Off\r\n\r\n- Short Press: Switch Source\r\n\r\n- Double Tap: Unpair Bluetooth', 'Active', 'Speaker'),
('P028', 'sony WH-1000XM4-Headset', 1099.00, '66f2e61341f4e.jpg', 'TOP FEATURES \r\n\r\n\r\n\r\n- Industry Leading Noise Cancellation\r\n\r\n- Ambient Sound Control\r\n\r\n- Premium Sound\r\n\r\n- Smart Listening Technology', 'Active', 'Headset');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` char(4) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`) VALUES
(4, 'P001', '66f2db4edd0f5.jpg'),
(5, 'P001', '66f2db4ee64fb.jpg'),
(6, 'P001', '66f2db4eef860.jpg'),
(7, 'P002', '66f2dba43fba3.jpg'),
(8, 'P002', '66f2dba45a8b5.jpg'),
(9, 'P002', '66f2dba474e7e.jpg'),
(10, 'P003', '66f2dbdb62397.jpg'),
(11, 'P003', '66f2dbdb756ee.jpg'),
(12, 'P004', '66f2dc430a3a2.jpg'),
(13, 'P004', '66f2dc4324c74.jpg'),
(14, 'P004', '66f2dc433fff3.jpg'),
(15, 'P004', '66f2dc435c9b1.jpg'),
(16, 'P005', '66f2dd4a26b75.jpg'),
(17, 'P005', '66f2dd4a40751.jpg'),
(18, 'P005', '66f2dd4a5aa21.jpg'),
(19, 'P005', '66f2dd4a7170f.jpg'),
(20, 'P006', '66f2dd85ddfec.jpg'),
(21, 'P006', '66f2dd8604aec.jpg'),
(22, 'P007', '66f2ddb5f1dc3.jpg'),
(23, 'P007', '66f2ddb610828.jpg'),
(24, 'P007', '66f2ddb628c59.jpg'),
(25, 'P008', '66f2ddef0b397.jpg'),
(26, 'P008', '66f2ddef25425.jpg'),
(27, 'P008', '66f2ddef4184e.jpg'),
(28, 'P009', '66f2de31bd8f4.jpg'),
(29, 'P009', '66f2de31d8485.jpg'),
(30, 'P010', '66f2de7472622.jpg'),
(31, 'P010', '66f2de748cc8a.jpg'),
(32, 'P010', '66f2de74a754b.jpg'),
(33, 'P011', '66f2ded02913f.jpg'),
(34, 'P012', '66f2df4f74756.jpg'),
(35, 'P012', '66f2df4f8fc05.jpg'),
(36, 'P013', '66f2df857351b.jpg'),
(37, 'P013', '66f2df858d96e.jpg'),
(38, 'P014', '66f2dfc6ac5a9.jpg'),
(39, 'P014', '66f2dfc6c4a8b.jpg'),
(40, 'P015', '66f2e02a38ad5.jpg'),
(41, 'P015', '66f2e02a539cf.jpg'),
(42, 'P016', '66f2e0c2d1d33.jpg'),
(43, 'P017', '66f2e13ea9451.jpg'),
(44, 'P017', '66f2e13ec4943.jpg'),
(45, 'P017', '66f2e13ee02b2.jpg'),
(46, 'P018', '66f2e1a4b07da.jpg'),
(47, 'P018', '66f2e1a4cb603.jpg'),
(48, 'P019', '66f2e212aaad3.jpg'),
(49, 'P019', '66f2e212b7eea.jpg'),
(50, 'P020', '66f2e2451782e.jpg'),
(51, 'P020', '66f2e2452c2a3.jpg'),
(52, 'P021', '66f2e29bab4c0.jpg'),
(53, 'P021', '66f2e29bc5f65.jpg'),
(54, 'P022', '66f2e2f4a260b.jpg'),
(55, 'P022', '66f2e2f4bd2b0.jpg'),
(56, 'P023', '66f2e321c71ba.jpg'),
(57, 'P023', '66f2e321d5aa3.jpg'),
(58, 'P024', '66f2e36ca8833.jpg'),
(59, 'P024', '66f2e36cb785f.jpg'),
(60, 'P025', '66f2e42e39804.jpg'),
(61, 'P025', '66f2e42e54ab1.jpg'),
(62, 'P026', '66f2e51773d90.jpg'),
(63, 'P026', '66f2e5178d5b8.jpg'),
(64, 'P027', '66f2e583cb58b.jpg'),
(65, 'P028', '66f2e613532f8.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `token`
--

CREATE TABLE `token` (
  `id` varchar(100) NOT NULL,
  `expire` datetime NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  `status` enum('Active','Blocked') DEFAULT 'Active',
  `verified` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `name`, `photo`, `role`, `status`, `verified`) VALUES
(1, 'smarttech@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Smarttech*', '66dd74776edb8.jpg', 'Admin', 'Active', 0),
(2, '2@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Kim Jisoo', '66e9a549e1216.jpg', 'Member', 'Blocked', 0),
(3, 'limweisheng0714@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Lim Wei Sheng', '66eed96f5b3c3.jpg', 'Member', 'Active', 0),
(4, 'vincent@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Vincent Kor', '66e5830fc49b0.jpg', 'Member', 'Active', 0),
(5, 'terence@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'Terence Tay', '66e58352b8e90.jpg', 'Member', 'Active', 0),
(10, 'bingbing8663@gmail.com', 'dd5fef9c1c1da1394d6d34b248c51be2ad740840', 'vincents', '66ed5605859cc.jpg', 'Member', 'Active', 0),
(12, 'admin_product@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'adminproduct', '66ef1abb81403.jpg', 'Admin_Product', 'Active', 0),
(13, 'admin_account@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'adminaccount', '66ef1b481731f.jpg', 'Admin_Account', 'Active', 0);

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` char(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_history`
--
ALTER TABLE `order_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `token`
--
ALTER TABLE `token`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wishlist_ibfk_1` (`user_id`),
  ADD KEY `wishlist_ibfk_2` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `order_history`
--
ALTER TABLE `order_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `token`
--
ALTER TABLE `token`
  ADD CONSTRAINT `token_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
