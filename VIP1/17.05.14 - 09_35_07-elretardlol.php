<?php exit() ?>--by elretardlol 70.79.64.24
-------------------------------------------------------------------------------------------
class 'ezEvadeLibrary'

function ezEvadeLibrary:__init()
	self.version = 1.0
end

function ezEvadeLibrary:GetVersion()
	return self.version
end

function ezEvadeLibrary:GetChampionInfo()

return {
    ["Aatrox"] = {charName = "Aatrox", skillshots = {
        ["Blade of Torment"] = {name = "Blade of Torment", spellName = "AatroxE", spellDelay = 250, projectileName = "AatroxBladeofTorment_mis.troy", projectileSpeed = 1200, range = 1075, radius = 100, type = "LINE"},
        ["AatroxQ"] = {name = "AatroxQ", spellName = "AatroxQ", spellDelay = 250, projectileName = "AatroxQ.troy", projectileSpeed = 450, range = 650, radius = 145, type = "CIRCULAR"},
	}},
	["Ahri"] = {charName = "Ahri", skillshots = {
        ["Orb of Deception"] = {name = "Orb of Deception", spellName = "AhriOrbofDeception", spellDelay = 250, projectileName = "Ahri_Orb_mis.troy", projectileSpeed = 1750, range = 900, radius = 100, type = "LINE"},
        ["Orb of Deception Back"] = {name = "Orb of Deception Back", spellName = "AhriOrbofDeception2", spellDelay = 750, projectileName = "Ahri_Orb_mis_02.troy", projectileSpeed = 915, range = 900, radius = 100, type = "LINE"},
        ["Charm"] = {name = "Charm", spellName = "AhriSeduce", spellDelay = 250, projectileName = "Ahri_Charm_mis.troy", projectileSpeed = 1600, range = 1000, radius = 60, type = "LINE"}
    }},
	["Amumu"] = {charName = "Amumu", skillshots = {
        ["Bandage Toss"] = {name = "Bandage Toss", spellName = "BandageToss", spellDelay = 250, projectileName = "Bandage_beam.troy", projectileSpeed = 2000, range = 1100, radius = 80, type = "LINE"}
    }},
	["Anivia"] = {charName = "Anivia", skillshots = {
        ["Flash Frost"] = {name = "Flash Frost", spellName = "FlashFrostSpell", spellDelay = 250, projectileName = "cryo_FlashFrost_mis.troy", projectileSpeed = 850, range = 1100, radius = 110, type = "LINE"}
    }},
	["Ashe"] = {charName = "Ashe", skillshots = {
        ["Enchanted Arrow"] = {name = "Enchanted Arrow", spellName = "EnchantedCrystalArrow", spellDelay = 250, projectileName = "EnchantedCrystalArrow_mis.troy", projectileSpeed = 1600, range = 25000, radius = 130, type = "LINE"},
    }},
	["Blitzcrank"] = {charName = "Blitzcrank", skillshots = {
       ["Rocket Grab"] = {name = "Rocket Grab", spellName = "RocketGrabMissile", spellDelay = 250, projectileName = "FistGrab_mis.troy", projectileSpeed = 1800, range = 1050, radius = 70, type = "LINE"}
    }},
	["Brand"] = {charName = "Brand", skillshots = {
        ["BrandBlaze"] = {name = "BrandBlaze", spellName = "BrandBlaze", spellDelay = 250, projectileName = "BrandBlaze_mis.troy", projectileSpeed = 1600, range = 1100, radius = 80, type = "LINE"},
        ["Pillar of Flame"] = {name = "Pillar of Flame", spellName = "BrandFissure", spellDelay = 250, projectileName = "BrandPOF_tar_green.troy", projectileSpeed = 900, range = 1100, radius = 240, type = "CIRCULAR"}
    }},
	["Braum"] = {charName = "Braum", skillshots = {
        ["Winter Bite"] = {spellKey = _Q, isCollision = true, name = "BraumQ", spellName = "BraumQ", spellDelay = 250, projectileName = "Braum_Base_Q_mis.troy", projectileSpeed = 1200, range = 1000, radius = 100, type = "LINE"},
		
	}},
    ["Caitlyn"] = {charName = "Caitlyn", skillshots = {
        ["Piltover Peacemaker"] = {name = "Piltover Peacemaker", spellName = "CaitlynPiltoverPeacemaker", spellDelay = 625, projectileName = "caitlyn_Q_mis.troy", projectileSpeed = 2200, range = 1300, radius = 90, type = "LINE"},
        ["Caitlyn Entrapment"] = {name = "Caitlyn Entrapment", spellName = "CaitlynEntrapment", spellDelay = 150, projectileName = "caitlyn_entrapment_mis.troy", projectileSpeed = 2000, range = 950, radius = 80, type = "LINE"},
    }},
	["Chogath"] = {charName = "Chogath", skillshots = {
        ["Rupture"] = {name = "Rupture", spellName = "Rupture", spellDelay = 0, projectileName = "rupture_cas_01_red_team.troy", projectileSpeed = 950, range = 950, radius = 250, type = "CIRCULAR"}
    }},
	["Corki"] = {charName = "Corki", skillshots = {
        ["Missile Barrage"] = {name = "Missile Barrage", spellName = "MissileBarrage", spellDelay = 250, projectileName = "corki_MissleBarrage_mis.troy", projectileSpeed = 2000, range = 1300, radius = 40, type = "LINE"},
        ["Missile Barrage big"] = {name = "Missile Barrage big", spellName = "MissileBarrage2", spellDelay = 250, projectileName = "Corki_MissleBarrage_DD_mis.troy", projectileSpeed = 2000, range = 1300, radius = 40, type = "LINE"}
    }},
	["Diana"] = {charName = "Diana", skillshots = {
        ["Diana Arc"] = {name = "DianaArc", spellName = "DianaArc", spellDelay = 250, projectileName = "Diana_Q_trail.troy", projectileSpeed = 1600, range = 1000, radius = 195, type=CIRCULAR},
    }},
	["Draven"] = {charName = "Draven", skillshots = {
        ["Stand Aside"] = {name = "Stand Aside", spellName = "DravenDoubleShot", spellDelay = 250, projectileName = "Draven_E_mis.troy", projectileSpeed = 1400, range = 1100, radius = 130, type = "LINE"},
        ["DravenR"] = {name = "DravenR", spellName = "DravenRCast", spellDelay = 500, projectileName = "Draven_R_mis2.troy", projectileSpeed = 2000, range = 25000, radius = 160, type = "LINE"},
    }},
	["Elise"] = {charName = "Elise", skillshots = {
        ["Cocoon"] = {name = "Cocoon", spellName = "EliseHumanE", spellDelay = 250, projectileName = "Elise_human_E_mis.troy", projectileSpeed = 1450, range = 1100, radius = 70, type = "LINE"}
    }},
	["Ezreal"] = {charName = "Ezreal", skillshots = {
        ["Mystic Shot"]             = {name = "Mystic Shot",      spellName = "EzrealMysticShot",      spellDelay = 250, projectileName = "Ezreal_mysticshot_mis.troy",  projectileSpeed = 2000, range = 1200,  radius = 80,  type = "LINE"},
        ["Essence Flux"]            = {name = "Essence Flux",     spellName = "EzrealEssenceFlux",     spellDelay = 250, projectileName = "Ezreal_essenceflux_mis.troy", projectileSpeed = 1500, range = 1050,  radius = 80,  type = "LINE"},
        ["Mystic Shot (Pulsefire)"] = {name = "Mystic Shot",      spellName = "EzrealMysticShotPulse", spellDelay = 250, projectileName = "Ezreal_mysticshot_mis.troy",  projectileSpeed = 2000, range = 1200,  radius = 80,  type = "LINE"},
        ["Trueshot Barrage"]        = {name = "Trueshot Barrage", spellName = "EzrealTrueshotBarrage", spellDelay = 1000, projectileName = "Ezreal_TrueShot_mis.troy",    projectileSpeed = 2000, range = 20000, radius = 160, type = "LINE"},
    }},
	["Fizz"] = {charName = "Fizz", skillshots = {
        ["Fizz Ultimate"] = {name = "Fizz ULT", spellName = "FizzMarinerDoom", spellDelay = 250, projectileName = "Fizz_UltimateMissile.troy", projectileSpeed = 1350, range = 1275, radius = 80, type = "LINE"},
    }},
	["Galio"] = {charName = "Galio", skillshots = {
        ["GalioResoluteSmite"] =  {name = "GalioResoluteSmite", spellName = "GalioResoluteSmite", spellDelay = 250, projectileName = "galio_concussiveBlast_mis.troy", projectileSpeed = 850, range = 2000, radius = 200, type = "CIRCULAR"},
    }},
	["Gragas"] = {charName = "Gragas", skillshots = {
        ["Barrel Roll"] = {name = "Barrel Roll", spellName = "GragasBarrelRoll", spellDelay = 250, projectileName = "gragas_barrelroll_mis.troy", projectileSpeed = 1000, range = 1115, radius = 180, type = "CIRCULAR"},
        ["Barrel Roll Missile"] = {name = "Barrel Roll Missile", spellName = "GragasBarrelRollMissile", spellDelay = 0, projectileName = "gragas_barrelroll_mis.troy", projectileSpeed = 1000, range = 1115, radius = 180, type = "CIRCULAR"},
    }},
	["Jayce"] = {charName = "Jayce", skillshots = {
        ["JayceShockBlast"] = {name = "JayceShockBlast", spellName = "JayceShockBlast2", spellDelay = 250, projectileName = "JayceOrbLightning.troy", projectileSpeed = 1450, range = 1050, radius = 70, type = "LINE"},
        ["JayceShockBlastCharged"] = {name = "JayceShockBlastCharged", spellName = "JayceShockBlast", spellDelay = 250, projectileName = "JayceOrbLightningCharged.troy", projectileSpeed = 2350, range = 1600, radius = 70, type = "LINE"},
    }},
	["Jinx"] = {charName = "Jinx", skillshots = {
        ["W"] =  {name = "Zap", spellName = "JinxWMissile", spellDelay = 600, projectileName = "Jinx_W_mis.troy", projectileSpeed = 3300, range = 1450, radius = 70, type = "LINE"},
        ["R"] =  {name = "Super Mega Death Rocket", spellName = "JinxRWrapper", spellDelay = 600, projectileName = "Jinx_R_Mis.troy", projectileSpeed = 2200, range = 20000, radius = 120, type = "LINE"},
    }}, 
	["Karma"] = {charName = "Karma", skillshots = {
        ["KarmaQ"] = {name = "KarmaQ", spellName = "KarmaQ", spellDelay = 250, projectileName = "TEMP_KarmaQMis.troy", projectileSpeed = 1700, range = 1050, radius = 90, type = "LINE"},
    }},
	["Karthus"] = {charName = "Karthus", skillshots = {
        ["Lay Waste"] = {name = "Lay Waste", spellName = "LayWaste", spellDelay = 750, projectileName = "LayWaste_point.troy", projectileSpeed = 1750, range = 875, radius = 140, type = "CIRCULAR"}
    }},
	["Kennen"] = {charName = "Kennen", skillshots = {
        ["Thundering Shuriken"] = {name = "Thundering Shuriken", spellName = "KennenShurikenHurlMissile1", spellDelay = 180, projectileName = "kennen_ts_mis.troy", projectileSpeed = 1700, range = 1050, radius = 50, type = "LINE"}
    }},
	["Khazix"] = {charName = "Khazix", skillshots = {
        ["KhazixW"] = {name = "KhazixW", spellName = "KhazixW", spellDelay = 250, projectileName = "Khazix_W_mis_enhanced.troy", projectileSpeed = 1700, range = 1025, radius = 70, type = "LINE"},
        ["khazixwlong"] = {name = "khazixwlong", spellName = "khazixwlong", spellDelay = 250, projectileName = "Khazix_W_mis_enhanced.troy", projectileSpeed = 1700, range = 1025, radius = 70, type = "LINE"},
    }},
	["KogMaw"] = {charName = "KogMaw", skillshots = {
        ["Living Artillery"] = {name = "Living Artillery", spellName = "KogMawLivingArtillery", spellDelay = 850, projectileName = "KogMawLivingArtillery_mis.troy", projectileSpeed = math.huge, range = 2200, radius = 225, type = "CIRCULAR"}
    }},
	["Leblanc"] = {charName = "Leblanc", skillshots = {
        ["Ethereal Chains"] = {name = "Ethereal Chains", spellName = "LeblancSoulShackle", spellDelay = 250, projectileName = "leBlanc_shackle_mis.troy", projectileSpeed = 1600, range = 960, radius = 70, type = "LINE"},
        ["Ethereal Chains R"] = {name = "Ethereal Chains R", spellName = "LeblancSoulShackleM", spellDelay = 250, projectileName = "leBlanc_shackle_mis_ult.troy", projectileSpeed = 1600, range = 960, radius = 70, type = "LINE"},
    }},
	["LeeSin"] = {charName = "LeeSin", skillshots = {
        ["Sonic Wave"] = {name = "Sonic Wave", spellName = "BlindMonkQOne", spellDelay = 250, projectileName = "blindMonk_Q_mis_01.troy", projectileSpeed = 1800, range = 1100, radius = 70, type = "LINE"}
    }},
	["Leona"] = {charName = "Leona", skillshots = {
        ["Zenith Blade"] = {name = "Zenith Blade", spellName = "LeonaZenithBlade", spellDelay = 250, projectileName = "Leona_ZenithBlade_mis.troy", projectileSpeed = 2000, range = 900, radius = 120, type = "LINE"},
        ["Leona Solar Flare"] = {name = "Leona Solar Flare", spellName = "LeonaSolarFlare", spellDelay = 250, projectileName = "Leona_SolarFlare_cas.troy", projectileSpeed = 2000, range = 1200, radius = 300, type = "CIRCULAR"}
    }},
	["Lucian"] = {charName = "Lucian", skillshots = {
        ["LucianQ"] =  {name = "LucianQ", spellName = "LucianQ", spellDelay = 350, projectileName = "Lucian_Q_laser.troy", projectileSpeed = math.huge, range = 570*2, radius = 65, type = "LINE"},
        ["LucianW"] =  {name = "LucianW", spellName = "LucianW", spellDelay = 300, projectileName = "Lucian_W_mis.troy", projectileSpeed = 1600, range = 1000, radius = 80, type = "LINE"},
    }},
	["Lulu"] = {charName = "Lulu", skillshots = {
        ["LuluQ"] = {name = "LuluQ", spellName = "LuluQ", spellDelay = 250, projectileName = "Lulu_Q_Mis.troy", projectileSpeed = 1450, range = 1000, radius = 50, type = "LINE"}
    }},
	["Lux"] = {charName = "Lux", skillshots = {
        ["Light Binding"] =  {name = "Light Binding", spellName = "LuxLightBinding", spellDelay = 250, projectileName = "LuxLightBinding_mis.troy", projectileSpeed = 1200, range = 1300, radius = 80, type = "LINE"},
        ["Lux LightStrike Kugel"] = {name = "LuxLightStrikeKugel", spellName = "LuxLightStrikeKugel", spellDelay = 250, projectileName = "LuxLightstrike_mis.troy", projectileSpeed = 1400, range = 1100, radius = 275, type = "CIRCULAR"},
        ["Lux Malice Cannon"] =  {name = "Lux Malice Cannon", spellName = "LuxMaliceCannon", spellDelay = 1375, projectileName = "Enrageweapon_buf_02.troy", projectileSpeed = math.huge, range = 3500, radius = 150, type = "LINE"},
    }},
	["Malphite"] = {charName = "Malphite", skillshots = {
        ["UFSlash"] = {name = "UFSlash", spellName = "UFSlash", spellDelay = 250, projectileName = "TEST", projectileSpeed = 1800, range = 1000, radius = 160, type = "LINE"},    
    }},
    ["Morgana"] = {charName = "Morgana", skillshots = {
        ["Dark Binding Missile"] = {name = "Dark Binding", spellName = "DarkBindingMissile", spellDelay = 250, projectileName = "DarkBinding_mis.troy", projectileSpeed = 1200, range = 1300, radius = 100, type = "LINE"},
    }},
    ["DrMundo"] = {charName = "DrMundo", skillshots = {
        ["Infected Cleaver"] = {name = "Infected Cleaver", spellName = "InfectedCleaverMissile", spellDelay = 250, projectileName = "dr_mundo_infected_cleaver_mis.troy", projectileSpeed = 2000, range = 1050, radius = 75, type = "LINE"},
    }},
    ["Nami"] = {charName = "Nami", skillshots = {
        ["NamiQ"] = {name = "NamiQ", spellName = "NamiQ", spellDelay = 250, projectileName = "Nami_Q_mis.troy", projectileSpeed = 1500, range = 1625, radius = 225, type=CIRCULAR}
    }},
	["Nautilus"] = {charName = "Nautilus", skillshots = {
        ["Dredge Line"] = {name = "Dredge Line", spellName = "NautilusAnchorDrag", spellDelay = 250, projectileName = "Nautilus_Q_mis.troy", projectileSpeed = 2000, range = 1080, radius = 80, type = "LINE"},
    }},
	["Nidalee"] = {charName = "Nidalee", skillshots = {
        ["Javelin Toss"] = {name = "Javelin Toss", spellName = "JavelinToss", spellDelay = 125, projectileName = "nidalee_javelinToss_mis.troy", projectileSpeed = 1300, range = 1500, radius = 60, type = "LINE"}
    }},
	["Nocturne"] = {charName = "Nocturne", skillshots = {
        ["NocturneDuskbringer"] =  {name = "NocturneDuskbringer", spellName = "NocturneDuskbringer", spellDelay = 250, projectileName = "NocturneDuskbringer_mis.troy", projectileSpeed = 1400, range = 1125, radius = 60, type = "LINE"},
    }},
    ["Olaf"] = {charName = "Olaf", skillshots = {
        ["Undertow"] = {name = "Undertow", spellName = "OlafAxeThrow", spellDelay = 250, projectileName = "olaf_axe_mis.troy", projectileSpeed = 1600, range = 1000, radius = 90, type = "LINE"}
    }},
	["Quinn"] = {charName = "Quinn", skillshots = {
        ["QuinnQ"] = {name = "QuinnQ", spellName = "QuinnQ", spellDelay = 250, projectileName = "Quinn_Q_missile.troy", projectileSpeed = 1550, range = 1050, radius = 80, type = "LINE"}
    }},
	["Rumble"] = {charName = "Rumble", skillshots = {
        ["RumbleGrenade"] =  {name = "RumbleGrenade", spellName = "RumbleGrenade", spellDelay = 250, projectileName = "rumble_taze_mis.troy", projectileSpeed = 2000, range = 950, radius = 90, type = "LINE"},
    }},
	["Sejuani"] = {charName = "Sejuani", skillshots = {
        ["SejuaniR"] = {name = "SejuaniR", spellName = "SejuaniGlacialPrisonCast", spellDelay = 250, projectileName = "Sejuani_R_mis.troy", projectileSpeed = 1600, range = 1200, radius = 110, type = "LINE"},    
    }},
	["Shen"] = {charName = "Shen", skillshots = {
        ["ShadowDash"] = {name = "ShadowDash", spellName = "ShenShadowDash", spellDelay = 0, projectileName = "shen_shadowDash_mis.troy", projectileSpeed = 3000, range = 575, radius = 50, type = "LINE"}
    }},
    ["Sivir"] = {charName = "Sivir", skillshots = { --hard to measure speed
        ["Boomerang Blade"] = {name = "Boomerang Blade", spellName = "SivirQ", spellDelay = 250, projectileName = "Sivir_Base_Q_mis.troy", projectileSpeed = 1350, range = 1275, radius = 101, type = "LINE"},
    }},
	["Sona"] = {charName = "Sona", skillshots = {
        ["Crescendo"] = {name = "Crescendo", spellName = "SonaCrescendo", spellDelay = 250, projectileName = "SonaCrescendo_mis.troy", projectileSpeed = 2400, range = 1000, radius = 150, type = "LINE"},		
    }},
    ["Swain"] = {charName = "Swain", skillshots = {
        ["Nevermove"] = {name = "Nevermove", spellName = "SwainShadowGrasp", spellDelay = 250, projectileName = "swain_shadowGrasp_transform.troy", projectileSpeed = 1000, range = 900, radius = 180, type = "CIRCULAR"}
    }},
	["Syndra"] = {charName = "Syndra", skillshots = {
        ["SyndraQ"] = {name = "SyndraQ", spellName = "SyndraQ", spellDelay = 200, projectileName = "Syndra_Q_cas.troy", projectileSpeed = 300, range = 800, radius = 180, type = "CIRCULAR"}
    }},
	["Thresh"] = {charName = "Thresh", skillshots = {
        ["ThreshQ"] = {name = "ThreshQ", spellName = "ThreshQ", spellDelay = 500, projectileName = "Thresh_Q_whip_beam.troy", projectileSpeed = 1900, range = 1100, radius = 65, type = "LINE"}
    }},
    ["TwistedFate"] = {charName = "TwistedFate", skillshots = {
        ["Loaded Dice"] = {name = "Loaded Dice", spellName = "WildCards", spellDelay = 250, projectileName = "Roulette_mis.troy", projectileSpeed = 1000, range = 1450, radius = 40, type = "LINE"},
    }},
    ["Urgot"] = {charName = "Urgot", skillshots = {
        ["Acid Hunter"] = {name = "Acid Hunter", spellName = "UrgotHeatseekingLineMissile", spellDelay = 175, projectileName = "UrgotLineMissile_mis.troy", projectileSpeed = 1600, range = 1000, radius = 60, type = "LINE"},
        ["Plasma Grenade"] = {name = "Plasma Grenade", spellName = "UrgotPlasmaGrenade", spellDelay = 250, projectileName = "UrgotPlasmaGrenade_mis.troy", projectileSpeed = 1750, range = 900, radius = 250, type = "CIRCULAR"},
    }},
	["Varus"] = {charName = "Varus", skillshots = {
        ["Varus Q Missile"] = {name = "Varus Q Missile", spellName = "VarusQ2", spellDelay = 0, projectileName = "VarusQ_mis.troy", projectileSpeed = 1900, range = 1600, radius = 70, type = "LINE"},
        ["Varus E"] = {name = "Varus E", spellName = "VarusE", spellDelay = 250, projectileName = "VarusEMissileLong.troy", projectileSpeed = 1500, range = 925, radius = 275, type = "CIRCULAR"},
        ["VarusR"] = {name = "VarusR", spellName = "VarusR", spellDelay = 250, projectileName = "VarusRMissile.troy", projectileSpeed = 1950, range = 1250, radius = 100, type = "LINE"},
    }},
	["Veigar"] = {charName = "Veigar", skillshots = {
        ["Dark Matter"] = {name = "VeigarDarkMatter", spellName = "VeigarDarkMatter", spellDelay = 250, projectileName = "2", projectileSpeed = 900, range = 900, radius = 225, type = "CIRCULAR"}
    }},
	--[[
	["Velkoz"] = {charName = "Velkoz", skillshots = {
		["VelkozE"] = { spellKey = _E, type = "LINE", spellName = "VelkozE", name = "VelkozE", range = 1100, projectileSpeed = 1500, radius = 120, projectileName = "Velkoz_base_E_terraform_mis.troy",},
		["VelkozW"] = { spellKey = _W, type = "CIRCULAR", spellName = "VelkozW", name = "VelkozW", range = 25000, projectileSpeed = 1200, projectileName = "Velkoz_Base_W_Turret.troy", radius = 90,},
		
		["VelkozQ"] = { spellKey = _Q, type = "LINE", spellName = "VelkozQ", name = "VelkozQ", range = 1100, radius = 90, projectileName = "Velkoz_Base_Q_mis.troy", },
		["VelkozQMissileSplit"] = { spellKey = ExtraSpell6, type = "LINE", spellName = "VelkozQMissileSplit", name = "VelkozQMissileSplit", range = 900, projectileName = "Velkoz_Base_Q_Split_mis.troy", radius = 90,},
	}},]]
	["Xerath"] = {charName = "Xerath", skillshots = {
		["XerathMageSpear"] = { spellKey = _E, type = "LINE", isCollision = true, isStun = true, spellName = "XerathMageSpear", name = "XerathMageSpear", projectileName = "Xerath_Base_E_mis.troy", range = 1050, projectileSpeed = 1600, radius = 70,},
	}},
	["Zed"] = {charName = "Zed", skillshots = {
        ["ZedShuriken"] = {name = "ZedShuriken", spellName = "ZedShuriken", spellDelay = 250, projectileName = "Zed_Q_Mis.troy", projectileSpeed = 1700, range = 925, radius = 50, type = "LINE"},
    }},
    ["Ziggs"] = {charName = "Ziggs", skillshots = {
        ["ZiggsQ"] =  {name = "ZiggsQ", spellName = "ZiggsQ", spellDelay = 250, projectileName = "ZiggsQ.troy", projectileSpeed = 1700, range = 2000, radius = 155, type = "LINE"},
        ["ZiggsW"] =  {name = "ZiggsW", spellName = "ZiggsW", spellDelay = 250, projectileName = "ZiggsW_mis.troy", projectileSpeed = 3000, range = 2000, radius = 210, type = "CIRCULAR"},
        ["ZiggsE"] =  {name = "ZiggsE", spellName = "ZiggsE", spellDelay = 250, projectileName = "ZiggsE_Mis_Large.troy", projectileSpeed = 3000, range = 2000, radius = 235, type = "CIRCULAR"},
    }},
	["Zyra"] = {charName = "Zyra", skillshots = {
		["Deadly Bloom"]   = {name = "Deadly Bloom", spellName = "ZyraQFissure", spellDelay = 750, projectileName = "zyra_Q_cas.troy", projectileSpeed = 1400, range = 825, radius = 220, type = "CIRCULAR"},
        ["Grasping Roots"] = {name = "Grasping Roots", spellName = "ZyraGraspingRoots", spellDelay = 250, projectileName = "Zyra_E_sequence_impact.troy", projectileSpeed = 1150, range = 1150, radius = 100,  type = "LINE"},
        ["Zyra Passive Death"] = {name = "Zyra Passive", spellName = "zyrapassivedeathmanager", spellDelay = 500, projectileName = "zyra_passive_plant_mis.troy", projectileSpeed = 2000, range = 1474, radius = 60,  type = "LINE"},
    }}
}

end

-------------------------------------------------------------------------------------------


local sVersion = 2.4

local evadeLib = ezEvadeLibrary()

local objSkillShots = {}
local procSkillShots = {}

local AllyIndex = {}
local EnemyIndex = {}

local skillShots = {}

local isDodging = true
local heroHoldPosition = true

local lastMovePos = Point(myHero.x,myHero.z)
local lastBestLastMove = nil
local lastUserMoveCommand = 0

local lastMoveCommand = 0
local lastBestPosition = Point(0,0)

local lastEvadePos = nil

local wayPointManager = WayPointManager()

local allMinions = minionManager(MINION_ALL, 500, player, MINION_SORT_HEALTH_ASC)

function DeleteSpell(spellName)
	local spell = skillShots[spellName]
	
	if spell and spell.spellParticle then
		return
	else
	skillShots[spellName] = nil
	end
end

function UpdateSpellEndPos(spellName)

	if skillShots[spellName] == nil or skillShots[spellName].dirSet then
		return
	end
	
	local spell = skillShots[spellName]
	local spellParticle = skillShots[spellName].spellParticle	
	
	if spellParticle then
	
	local startPos = spell.startPos
	local cPos = spellParticle
	
	local lastPos = skillShots[spellName].lastPos or startPos
	local dir = (Vector(cPos.x,0,cPos.z) - Vector(lastPos.x,0,lastPos.z)):normalized()
		
	if spell.info.type == "LINE" then			
	
		if spell.endPos then
		local testDir = (Vector(spell.endPos.x,0,spell.endPos.z) - Vector(startPos.x,0,startPos.z)):normalized()
		
		if skillShots[spellName].dir and skillShots[spellName].dir:dotP(dir) > .99 and dir:dotP(testDir) < .90 then
			spell.endPos = Vector(startPos) + dir * spell.info.range
			skillShots[spellName].dirSet = true
		else
			skillShots[spellName].dir = dir
			DelayAction(UpdateSpellEndPos, .02, {spellName}) --needs constant checking
		end		
	else
	
	if skillShots[spellName].dir and skillShots[spellName].dir:dotP(dir) > .99 and skillShots[spellName].dirSet == nil then	
		spell.endPos = Vector(startPos) + dir * spell.info.range
		skillShots[spellName].dirSet = true
		
	else
		skillShots[spellName].dir = dir
		DelayAction(UpdateSpellEndPos, .02, {spellName}) --needs constant checking
	end
	
	end
	
	skillShots[spellName].lastPos = Vector(spellParticle.x,0,spellParticle.z)
	
	
	
	else	
	if spell.endPos == nil then
	spell.endPos = Vector(spellParticle)
	end
	
	end
	
	GetBestPosition()
	end
end

function IsAllySpell(object, spellInfo)
	local charName = spellInfo.charName

	for i = 1, heroManager.iCount do
		local hero = heroManager:GetHero(i)		
		if hero.team == player.team and charName == hero.charName then
			if spellInfo.type == "LINE" and GetDistance(object, hero) < 50 then
				return true
			elseif spellInfo.type == "CIRCULAR" and GetDistance(object, hero) < spellInfo.range then
				return true
			end
		end
	end
	
	return false
end

function OnProcessSpell(hero, spell)

	if hero.type == "obj_AI_Hero" and procSkillShots[spell.name] then
		if hero.team ~= player.team then
		
		local spellInfo = procSkillShots[spell.name]
		
		if Menu.SkillSettings[spellInfo.charName .. "SkillSettings"][spellInfo.spellName] == false then return end
		
		if GetDistance(myHero, spell.startPos) < spellInfo.range + 1000 then
		
		local endPosition = Vector(spell.endPos)
		local endTick = 0
		
		--print(spell.projectileID)
		
		if spellInfo.type == "LINE" then
			endTick = spellInfo.spellDelay + spellInfo.range / spellInfo.projectileSpeed * 1000
			
			local direction = (Vector(spell.endPos.x,0,spell.endPos.z) - Vector(spell.startPos.x,0,spell.startPos.z)):normalized()
			endPosition = Vector(spell.startPos) + direction * spellInfo.range
		else
			endTick = spellInfo.spellDelay
		end
				
		skillShots[spell.name] = {startTime = GetTick(), endTime = GetTick() + endTick, startPos = Vector(spell.startPos), endPos = endPosition, info = spellInfo}
		GetBestPosition()		
		DelayAction(DeleteSpell, endTick/1000, {spell.name})
		

		end
		
		else
		--allySkillShots[spell.name] = {startTime = GetTick(), endPos}
		--DelayAction(DeleteSpell, endTick/1000, {spell.name})
		end		
	end
end

function OnCreateObj(object)

	if objSkillShots[object.name] then

		local spellInfo = objSkillShots[object.name]
		
		if Menu.SkillSettings[spellInfo.charName .. "SkillSettings"][spellInfo.spellName] == false then return end
		
		if IsAllySpell(object,spellInfo) then --object placement?
			return
		end		
		
		--print(object.name)
		--print(GetDistance(object,myHero))
		
		if GetDistance(myHero, object) < spellInfo.range + 1000 and skillShots[spellInfo.spellName] == nil then
				
		local endTick = 0
		local endPosition = nil
		
		if spellInfo.type == "LINE" then
			endTick = spellInfo.range / spellInfo.projectileSpeed * 1000
		else
			endTick = spellInfo.spellDelay
			endPosition = Vector(object)
		end		

		skillShots[spellInfo.spellName] = {startTime = GetTick(), endTime = GetTick() + endTick, startPos = Vector(object.x,myHero.y,object.z), endPos = endPosition, info = spellInfo, spellParticle = object, charIndex = EnemyIndex[spellInfo.charName]}
		DelayAction(UpdateSpellEndPos, .02, {spellInfo.spellName}) --needs constant checking
		DelayAction(DeleteSpell, endTick/1000, {spellInfo.spellName})
					
		elseif skillShots[spellInfo.spellName] then		
			skillShots[spellInfo.spellName].spellParticle = object
		end
	end
end

function OnDeleteObj(object)
	if objSkillShots[object.name] then	
		for spellName, spell in pairs(skillShots) do			
			if spell.spellParticle and GetDistance(spell.spellParticle, object) < 25 then
				skillShots[spellName] = nil
			end		
		end		
	end
end

function canHeroEvade(hero, spell)
	local radius = Menu.DodgeBuffer

	if spell.startPos and spell.endPos then

	local evadeTime = 0
	local spellHitTime = math.huge	
	
	local heroPoint = Point(hero.x, hero.z)
	
	if spell.info.type == "LINE" then		
		local startPos = Point(spell.startPos.x, spell.startPos.z)
		local endPos = Point(spell.endPos.x, spell.endPos.z)
		local projection = heroPoint:perpendicularFoot(Line(startPos, endPos))
		local spellPos = Point(spell.startPos.x,spell.startPos.z)
		
		evadeTime = math.max(0,(spell.info.radius + radius) - projection:distance(heroPoint))/hero.ms
		
		if spell.spellParticle then
		spellPos = Point(spell.spellParticle.x,spell.spellParticle.z)
		spellHitTime = projection:distance(spellPos)/spell.info.projectileSpeed
		else
		spellHitTime = spell.info.spellDelay - (GetTick() - spell.startTime) + projection:distance(spellPos)/spell.info.projectileSpeed
		end
				
		if spell.info.projectileSpeed == math.huge then --fix this part
		spellHitTime = spell.info.spellDelay - (GetTick() - spell.startTime)
		end
		
	else
		local endPos = Point(spell.endPos.x, spell.endPos.z)
		evadeTime = math.max(0,(spell.info.radius + radius) - endPos:distance(heroPoint))/hero.ms
		spellHitTime = spell.info.spellDelay - (GetTick() - spell.startTime)	
	end
		
	return spellHitTime > evadeTime, spellHitTime
	
	end
	
	return true
end

function canSpellHit(spell, pos, timeElapsed)
	local radius = Menu.DodgeBuffer

	if spell.startPos and spell.endPos then
	
	if spell.info.type == "LINE" then		
		local startPos = Point(spell.startPos.x, spell.startPos.z)
		local endPos = Point(spell.endPos.x, spell.endPos.z)
		local dir = (endPos - startPos):normalize()		
		
		local spellTime = math.max(0,(GetTick()-spell.startTime) + timeElapsed - spell.info.spellDelay)		
		local spellPos = startPos + dir * spell.info.projectileSpeed * (spellTime/1000)
						
		return pos:distance(spellPos) <= spell.info.radius + radius	
	else
		local spellFutureTick = GetTick() + timeElapsed
		local spellHitTime = spell.startTime + spell.info.spellDelay
		local endPos = Point(spell.endPos.x, spell.endPos.z)

		return pos:distance(endPos) <= spell.info.radius + radius 
			and spellFutureTick > spellHitTime
	end
		
	
	end
	
	return false
end

function playerInSkillShot(spell)
	return inSkillShot(spell, Point(myHero.visionPos.x, myHero.visionPos.z), Menu.DodgeBuffer)
end

function inSkillShot(spell, position, radius)

	local spellPos = Point(spell.startPos.x, spell.startPos.z)
	local endPos = Point(spell.endPos.x, spell.endPos.z)
	
	if spell.info.type == "LINE" then	
		if spell.spellParticle then --leave little space at back of skillshot
			local dir = (Vector(spell.endPos.x,0,spell.endPos.z) - Vector(spell.startPos.x,0,spell.startPos.z)):normalized()
			spellPos = Vector(spell.startPos) + dir * (GetDistance(spell.spellParticle, spell.startPos) - spell.info.radius)
			spellPos = Point(spellPos.x,spellPos.z)
		end
		
		local skillshotSegment = LineSegment(spellPos, endPos)
		local projection = position:perpendicularFoot(Line(spellPos, endPos))

		return position:distance(projection) <= spell.info.radius + radius and skillshotSegment:distance(projection) < 3
	else
		return position:distance(endPos) <= spell.info.radius + radius
	end
end

function GetLinePosition(spell)
	local spellPos = Point(spell.startPos.x, spell.startPos.z)
	local endPos = Point(spell.endPos.x, spell.endPos.z)
		
	local heroPoint = Point(myHero.x, myHero.z)
	local projection = heroPoint:perpendicularFoot(Line(spellPos, endPos))	
	local dir = (heroPoint - projection):normalized()	
			
	return projection + dir * (spell.info.radius + Menu.DodgeBuffer+50)
end

function GetCircularPosition(spell)
	local heroPoint = Point(myHero.x, myHero.z)
	local endPos = Point(spell.endPos.x,spell.endPos.z)
	local dir = (heroPoint - endPos):normalized()
	
	return endPos + dir * (spell.info.radius + Menu.DodgeBuffer+50)
end

function GetBestPosition()
	local bestPosition = nil
	local closestPosition = math.huge
	
	local posChecked = 0	
	local posRadius = 50	
	local radiusIndex = 0
	
	local backupPos = nil
	
	while posChecked < 50 do
	radiusIndex = radiusIndex + 1
	
	local curRadius = radiusIndex * (2*posRadius)
	local curCircleChecks = math.ceil((2*math.pi*curRadius)/(2*posRadius))
	
	for i=1, curCircleChecks, 1 do
	
	posChecked = posChecked + 1
	local cRadians = (2*math.pi/(curCircleChecks-1))*i
	local pos = Point(myHero.x + curRadius*math.cos(cRadians), myHero.z + curRadius*math.sin(cRadians) )
	local inDanger = false
				
	if not CheckWallCollision(pos) then
	
	for spellName, spell in pairs(skillShots) do
		if spell.startPos and spell.endPos then
			if inSkillShot(spell, pos, Menu.DodgeBuffer) then
				inDanger = true
				--break
			end
		end
	end	
	
	if not inDanger and (Menu.UnitCollision == false or CheckUnitCollision(pos) == false) then
				
		if lastMovePos and lastMovePos:distance(pos) < closestPosition and canHeroWalkToPos(pos) then
			bestPosition = pos
			closestPosition = lastMovePos:distance(pos)			
		elseif lastMovePos == nil then
			lastBestPosition = pos
			return pos
		end
		
		if backupPos == nil then
			backupPos = pos
		end
	end
	
	end	
	end	
	end
	
	if bestPosition == nil then
		bestPosition = backupPos
	end
	
	lastBestPosition = bestPosition
	return bestPosition	
end

function CheckUnitCollision(pos)
	for _, minion in pairs(allMinions.objects) do	
		if GetDistance(minion,Vector(pos.x,pos.y)) < 75 then
			return true
		end
	end
	
	for i = 1, heroManager.iCount do
	local hero = heroManager:GetHero(i)		
		if hero.name ~= myHero.name and GetDistance(hero,Vector(pos.x,pos.y)) < 75 then
			return true
		end		
	end	
	
	return false
end

function CheckWallCollision(pos)
	if Menu.CheckWall then
		for i=1, 5, 1 do
			local cRadians = (2*math.pi/5)*i
			local tPos = Point(pos.x + 75*math.cos(cRadians), pos.y + 75*math.sin(cRadians) )

			if IsWall(D3DXVECTOR3(tPos.x, myHero.y, tPos.y)) then
				return true
			end
		end
		
		return false
	else
		return IsWall(D3DXVECTOR3(pos.x, myHero.y, pos.y))
	end
end

function canHeroWalkToPos(pos)
	local heroPoint = Point(myHero.x, myHero.z)
	local walkDir = (pos - heroPoint):normalize()
	
	local checkDist = 50
	local checks = pos:distance(heroPoint)/checkDist
	
	for i=1, checks, 1 do
		local pos = heroPoint + walkDir * i * checkDist		
				
		if Menu.UnitCollision and CheckUnitCollision(pos) then
			return false
		end
		
		for spellName, spell in pairs(skillShots) do
			if spell.startPos and spell.endPos then
				local timeElapsed = 1000 * heroPoint:distance(pos)/myHero.ms
				
				if canSpellHit(spell, pos, timeElapsed) then
					return false
				end
			end
		end		
	end
	
	return true
end

function GetLongestPoint(pos)
	
	local longPos = pos
	
	local dir = (pos - Point(myHero.x,myHero.z)):normalize()
	local checkDist = 50
	local checkIndex = 0
	
	while longPos:distance(pos) < 300 do
	
	checkIndex = checkIndex + 1
	local tPos = Point(myHero.x,myHero.z) + dir * checkIndex * checkDist	
	
	if IsWall(D3DXVECTOR3(tPos.x, myHero.y, tPos.y)) then
		return longPos
	end
	
	for spellName, spell in pairs(skillShots) do
		if spell.startPos and spell.endPos then
			if inSkillShot(spell, tPos, Menu.DodgeBuffer) then
				return longPos
			end
		end
	end	
	
	longPos = tPos
	end
	
	return longPos
end

function GetBestLastMove()
	if lastMovePos == nil then return nil end

	local bestPosition = nil
	local maxBestPos = 0
	
	local posChecked = 0	
	local posRadius = 10	
	local radiusIndex = 0
	
	--while posChecked < 25 do
	radiusIndex = radiusIndex + 1
	
	local curRadius = 100 --radiusIndex * (2*posRadius)
	local curCircleChecks = math.ceil((2*math.pi*curRadius)/(2*posRadius))
	
	for i=1, curCircleChecks, 1 do
	
	posChecked = posChecked + 1
	local cRadians = (2*math.pi/(curCircleChecks-1))*i
	local pos = Point(myHero.x + curRadius*math.cos(cRadians), myHero.z + curRadius*math.sin(cRadians) )
	local inDanger = false

	local heroPoint = Point(myHero.x,myHero.z)
	local lastMoveDir = (lastMovePos - heroPoint):normalize()
	local curMoveDir = (pos - heroPoint):normalize()
		
	if not IsWall(D3DXVECTOR3(pos.x, myHero.y, pos.y)) then --and pos:distance(lastMovePos) < heroPoint:distance(lastMovePos) then--lastMoveDir:dotP( curMoveDir ) > .5 then
	
	for spellName, spell in pairs(skillShots) do
		if spell.startPos and spell.endPos then
			if inSkillShot(spell, pos, Menu.DodgeBuffer + 25) then
				inDanger = true
				--break
			end
		end
	end
		
	--DrawCircle(pos.x, myHero.y, pos.y, 50, 0x00FF00)
	if not inDanger and maxBestPos < Vector(lastMoveDir.x,0,lastMoveDir.y):dotP(Vector(curMoveDir.x,0,curMoveDir.y)) then
		maxBestPos = Vector(lastMoveDir.x,0,lastMoveDir.y):dotP(Vector(curMoveDir.x,0,curMoveDir.y))
		bestPosition = pos
		
	end
	
	end
	
	end	
	
	--end
	if bestPosition == nil then return nil end
	
	local tPos = GetLongestPoint(bestPosition)
	lastBestLastMove = tPos
	
	return tPos
end

function checkHeroDirection(spell)
	local waypoints = wayPointManager:GetWayPoints(myHero)
	
	if waypoints and #waypoints > 1 then
		local waypoint1 = Vector(waypoints[1].x, 0, waypoints[1].y)
		local waypoint2 = Vector(waypoints[2].x, 0, waypoints[2].y)
		local dir = (waypoint2 - waypoint1):normalized()
		
		local predPos = Vector(myHero) + dir * 25
		
		if inSkillShot(spell, Point(predPos.x, predPos.z), Menu.DodgeBuffer) then
			return true			
		end
	end
	
	return false
end

function DodgeSkillShots()
	local playerInDanger = false
	local bestPosInDanger = false
	local playerWalkInDanger = false
	
	--reach bestPosYet?
	
	local waypoints = wayPointManager:GetWayPoints(myHero)	
	if #waypoints < 2 then
		lastMovePos = nil
	end
	
	for spellName, spell in pairs(skillShots) do
					
		if spell.startPos and spell.endPos then
				
		if playerInSkillShot(spell) then --and not canHeroEvade(myHero, Menu.DodgeBuffer, spell) then 
			playerInDanger = true			
		else
			playerWalkInDanger = checkHeroDirection(spell)
		end
		
		if lastBestPosition and inSkillShot(spell, Point(lastBestPosition.x, lastBestPosition.y), Menu.DodgeBuffer) then
			bestPosInDanger = true
		end
		
		end
	end
	
	if bestPosInDanger then --and lastBestPosition:distance(Point(myHero.x,myHero.z)) < 10 then
		lastBestPosition = GetBestPosition()
	end
	
	if playerWalkInDanger and isDodging == false then
		
		if waypoints and #waypoints > 1 and not canHeroWalkToPos(Point(waypoints[2].x, waypoints[2].y)) then
				
		local pos = GetBestLastMove()
		if pos then
			--Packet('S_MOVE', {x = pos.x, y = pos.y}):send()
			lastBestLastMove = pos
			myHero:MoveTo(pos.x, pos.y)	
			lastEvadePos = pos
		else
			--myHero:HoldPosition()
		end
		
		end
	end
	
	if isDodging == false and playerInDanger then
		isDodging = true
	elseif not playerInDanger then
		isDodging = false
	end
	
	if lastBestPosition then
	if isDodging and GetTick() - lastMoveCommand > 25 then		
		local pos = lastBestPosition		
		lastMoveCommand = GetTick()
		lastEvadePos = pos
		myHero:MoveTo(pos.x, pos.y)		
		
		--Packet('S_MOVE', {x = pos.x, y = pos.y}):send()		
	elseif isDodging and lastBestPosition:distance(Point(myHero.x,myHero.z)) < 10 then
		isDodging = false
	end
	
	else
	
	if playerInDanger then
	lastBestPosition = GetBestPosition()
	end
	
	end
end

function OnTick()
	if Menu.StopDodging == false and Menu.DodgeSkillShots then
	allMinions:update()
	DodgeSkillShots()
	end
end

function OnDraw()

	if Menu.DrawSkillShots then		
				
		for spellName, spell in pairs(skillShots) do
						
			if spell.startPos and spell.endPos then
								
			if spell.info.type == "LINE" then
				if spell.spellParticle then
				local dir = (Vector(spell.endPos.x,0,spell.endPos.z) - Vector(spell.startPos.x,0,spell.startPos.z)):normalized()
				local spellPos = Vector(spell.startPos) + dir * GetDistance(spell.spellParticle, spell.startPos)
				
				DrawLine3D(spellPos.x, spellPos.y, spellPos.z, spell.endPos.x, spell.endPos.y, spell.endPos.z, spell.info.radius, ARGB(125, 125, 0, 0))
				else
				DrawLine3D(spell.startPos.x, spell.startPos.y, spell.startPos.z, spell.endPos.x, spell.endPos.y, spell.endPos.z, spell.info.radius, ARGB(125, 125, 0, 0))
				end
			else
                DrawCircle(spell.endPos.x, spell.endPos.y, spell.endPos.z, spell.info.radius, 0x00FF00)
            end
			
			end
		end
	end	
end

function OnSendPacket(p)
	if Menu.StopDodging == false and Menu.DodgeSkillShots and Menu.BlockMovement then
	
	packet = Packet(p)
	packetName = Packet(p):get('name')

	if packetName == 'S_MOVE' and packet:get('type') == 2 then
				
		local movePos = Point(packet:get('x'),packet:get('y'))		
		if lastEvadePos and lastEvadePos:distance(movePos) > 50
			and GetTick() - lastUserMoveCommand > 25 then
		lastMovePos = movePos
		lastUserMoveCommand = GetTick()
		
		local bestPos = GetBestPosition()
		
		if bestPos and bestPos:distance(lastBestPosition) > 100 then
		lastBestPosition = bestPos
		--never happens
		end
		
		end
	end
	
	if isDodging then
		if packetName == 'S_MOVE' then
			local movePos = Point(packet:get('x'),packet:get('y'))
			if lastBestPosition and lastBestPosition:distance(movePos) > 10 then
			packet:block()
			myHero:MoveTo(lastBestPosition.x, lastBestPosition.y)
			lastEvadePos = lastBestPosition
			end
		elseif packetName == 'S_CAST' then
			packet:block()		
		end
		
		return
	end
	
	if Menu.DangerArea and packetName == 'S_MOVE' and isDodging == false and packet:get('type') == 2 then
		local movePos = Point(packet:get('x'),packet:get('y'))
		
				
		local waypoints = wayPointManager:GetWayPoints(myHero)		
		if waypoints and #waypoints > 1 then
		
		local waypoint2 = Point(waypoints[2].x, waypoints[2].y)
		if not canHeroWalkToPos(waypoint2) then
	
		for spellName, spell in pairs(skillShots) do
						
			if spell.startPos and spell.endPos then			
			--Check if hero walking into skillshot
			
			local dir = (waypoint2-Point(myHero.x,myHero.z)):normalized()
			dir = Vector(dir.x,0,dir.y)
						
			local predPos = Vector(myHero) + dir * 100
								
			if not playerInSkillShot(spell) and inSkillShot(spell, Point(predPos.x, predPos.z), Menu.DodgeBuffer) then
				packet:block()
				
				local pos = GetBestLastMove()
				if pos then
				lastMoveCommand = GetTick()
				Packet('S_MOVE', {x = pos.x, y = pos.y}):send()
				lastEvadePos = pos
				end
			end
			
			end
		end
		end
		
		end
	end
	
	end
end

function OnLoad()
	PrintChat("<font color=\"#3F92D2\" >ezEvade v" .. sVersion .. " Loaded</font>")
	
	DelayAction(CheckForUpdates, math.random(1,5), {})
	
	Menu = scriptConfig("ezEvade v" .. sVersion, "ezEvade" .. sVersion)
	Menu:addParam("DrawSkillShots", "Draw Skillshots", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("DodgeSkillShots", "Dodge Skillshots", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("StopDodging", "Stop Dodging Key", SCRIPT_PARAM_ONKEYDOWN, false, 17)
	Menu:addParam("DangerArea", "Stop moving into Danger Area", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("BlockMovement", "Block Movement", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("UnitCollision", "Check Unit Collision", SCRIPT_PARAM_ONOFF, false)
	Menu:addParam("CheckWall", "Check Wall Collision", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("DodgeBuffer", "Dodge Buffer", SCRIPT_PARAM_SLICE, 100, 0, 500, 0)
		
	Menu:addSubMenu("Skill Settings", "SkillSettings")
	
	local championInfo = evadeLib:GetChampionInfo()	
	local spellKeyStr = { [_Q] = "Q", [_W] = "W", [_E] = "E", [_R] = "R" } 
	
	for i = 1, heroManager.iCount do
		local hero = heroManager:GetHero(i)
		
		if hero.team ~= player.team then
			EnemyIndex[hero.charName] = i
					
			if championInfo[hero.charName] and championInfo[hero.charName].skillshots then
				
				Menu.SkillSettings:addSubMenu(hero.charName,hero.charName .. "SkillSettings")
				
				for spellName, spell in pairs(championInfo[hero.charName].skillshots) do
					spell.charName = hero.charName
					procSkillShots[spell.spellName] = spell					
					
					if spell.projectileName then
					objSkillShots[spell.projectileName] = spell
					end
					
					local spellDefaultOption = true
					if spell.type == "CIRCULAR" then
						spellDefaultOption = false
					end
					
					Menu.SkillSettings[hero.charName .. "SkillSettings"]:addParam(spell.spellName, spell.spellName, SCRIPT_PARAM_ONOFF, spellDefaultOption)
				end
			end						
		else
			AllyIndex[hero.charName] = i
		end
	end
end

function GetTick()
	return GetGameTimer() * 1000
end

----------------AUTO Update------------------------------

function DownloadScript (scriptName, scriptVersion, url, scriptPath)
	local UPDATE_TMP_FILE = LIB_PATH.. scriptName .. "Tmp.txt"
	
	DownloadFile(url, UPDATE_TMP_FILE, 
		function ()
		
		file = io.open(UPDATE_TMP_FILE, "rb")
		if file ~= nil then
        downloadContent = file:read("*all")
        file:close()
        os.remove(UPDATE_TMP_FILE)
		end
		
	
	if downloadContent then
		
		file = io.open(scriptPath, "w")
        
		if file then
            file:write(downloadContent)
            file:flush()
            file:close()
            print("Successfully updated " .. scriptName .. " to Version " .. scriptVersion)
			print("Please press F9 to reload script.")
        else
            print("Error updating!")
        end
		
	end
	
		
	end)	
end

function ReadLastUpdateTime ()

	local updateTimeFile = LIB_PATH.."ezEvadeUpdateTime"
	
	file = io.open(updateTimeFile, "rb")
	if file ~= nil then
    content = file:read("*all")
    file:close()
	
	return tonumber(content)
	end
	
	return 0
end

function WriteLastUpdateTime ()
	local updateTimeFile = LIB_PATH.."ezEvadeUpdateTime"
	
	file = io.open(updateTimeFile, "w")
     
	if file then
        file:write(os.time())
        file:flush()
        file:close()
    end
end

function CheckForUpdates ()

	local lastUpdateTime = ReadLastUpdateTime()
			
	if true then
	--if os.time()-lastUpdateTime > 86400 and os.time() > lastUpdateTime then --a day has passed

	local URL = "https://bitbucket.org/Xgs/bol/raw/master/Versions.txt"
	local UPDATE_TMP_FILE = LIB_PATH.."TmpVersions.txt"
	
	DownloadFile(URL, UPDATE_TMP_FILE, 
	function ()
		file = io.open(UPDATE_TMP_FILE, "rb")
		if file ~= nil then
        versionTextContent = file:read("*all")
        file:close()
        os.remove(UPDATE_TMP_FILE)
		end

	if versionTextContent then		
		local url = "https://bitbucket.org/Xgs/bol/raw/master/ezEvade.lua"
		Update(versionTextContent, "ezEvade", sVersion, url, SCRIPT_PATH.."ezEvade.lua")
	end
		
	end)
	
	WriteLastUpdateTime()
	end
	
end
	
function Update(versionText, scriptName, scriptVersion, url, scriptPath)
	local content = versionText
	
	--print("Checking updates for " .. scriptName .. "...")
	
    if content then		
        tmp, sstart = string.find(content, "\"" .. scriptName .. "\" : \"")
        if sstart then
            send, tmp = string.find(content, "\"", sstart+1)
        end
		
        if send then
            Version = tonumber(string.sub(content, sstart+1, send-1))
        end
		
		if (Version ~= nil) and (Version > scriptVersion) then 
		
		print("Found update for " .. scriptName .. ", downloading...")
		DelayAction(DownloadScript,2,{scriptName, Version, url, scriptPath})
		
			
        elseif (Version ~= nil) and (Version <= scriptVersion) then
            --print("No updates found. Latest Version: " .. Version)
        end
    end
end
----------------AUTO Update------------------------------