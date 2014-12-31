<?php exit() ?>--by Kain 71.84.65.69
--[[The Storm's Fury for Janna - Made by Pain]]--
if myHero.charName ~= "Janna" then return end

QWidth = 150
QDelay = 0.240
QSpeed = 750
QRange = 1500
delay = 250

--[[Auto Shield Code]]--
local typeshield
local spellslot
local typeheal
local healslot
local typeult
local ultslot
if myHero.charName == "Janna" then
	typeshield = 1
	spellslot = _E
end
local range = 0
local healrange = 0
local ultrange = 0
local shealrange = 300
local lisrange = 700
local sbarrier = nil
local sheal = nil
local useitems = true
local spelltype = nil
local casttype = nil
--[[End Of Auto Shield Code]]--

function OnLoad()
menu = scriptConfig("The Storm's Fury", "Janna")
menu:addParam("UseQ", "Auto Q Target", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("Q"))
menu:addParam("UseW", "Auto W Target", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("W"))
menu:addParam("ManualOverride", "Manual Override", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("A"))
menu:permaShow("UseQ")
menu:permaShow("UseW")
menu:permaShow("ManualOverride")

if typeshield ~= nil then
for i=1, heroManager.iCount do
	local teammate = heroManager:GetHero(i)
	if teammate.team == myHero.team then menu:addParam("teammateshield"..i, "Shield "..teammate.charName, SCRIPT_PARAM_ONOFF, true) end
	end
	menu:addParam("maxhppercent", "Max percent of hp", SCRIPT_PARAM_SLICE, 100, 0, 100, 0)	
		menu:addParam("mindmgpercent", "Min dmg percent", SCRIPT_PARAM_SLICE, 20, 0, 100, 0)
		menu:addParam("mindmg", "Min dmg approx", SCRIPT_PARAM_INFO, 0)
		menu:addParam("skillshots", "Shield Skillshots", SCRIPT_PARAM_ONOFF, true)
		menu:addParam("shieldcc", "Auto Shield Hard CC", SCRIPT_PARAM_ONOFF, true)
		menu:addParam("shieldslow", "Auto Shield Slows", SCRIPT_PARAM_ONOFF, true)
		menu:addParam("playerradius", "Player radius", SCRIPT_PARAM_SLICE, 50, 0, 100, 0)
		menu:addParam("drawcircles", "Draw Range", SCRIPT_PARAM_ONOFF, true)
		menu:permaShow("mindmg")
	end

ts = TargetSelector(TARGET_LOW_HP_PRIORITY, 1700, DAMAGE_MAGICAL, false)
ts.name = "Janna"
menu:addTS(ts)

PriorityOnLoad()
--[[Rainbow Text Made with: http://www.tektek.org/color/]]--
PrintChat("<font color='#FF0000'>L</font><font color='#FF2C00'>o</font><font color='#FF5800'>a</font><font color='#FF8400'>d</font><font color='#FFB000'>e</font><font color='#FFDC00'>d</font><font color='#FFff00'> </font><font color='#D3ff00'>T</font><font color='#A7ff00'>h</font><font color='#7Bff00'>e</font><font color='#4Fff00'> </font><font color='#23ff00'>S</font><font color='#00ff00'>t</font><font color='#00ff2C'>o</font><font color='#00ff58'>r</font><font color='#00ff84'>m</font><font color='#00ffB0'>'</font><font color='#00ffDC'>s</font><font color='#00ffff'> </font><font color='#00DCff'>F</font><font color='#00B0ff'>u</font><font color='#0084ff'>r</font><font color='#0058ff'>y</font><font color='#002Cff'> </font><font color='#0000ff'>f</font><font color='#2300ff'>o</font><font color='#4F00ff'>r</font><font color='#7B00ff'>,</font><font color='#A700ff'> </font><font color='#D300ff'>J</font><font color='#FF00ff'>a</font><font color='#FF00DC'>n</font><font color='#FF00B0'>n</font><font color='#FF0084'>a</font><font color='#FF0058'> </font>")
PrintChat("<font color='#C80046'>C</font><font color='#C8002F'>r</font><font color='#C80016'>e</font><font color='#C70300'>d</font><font color='#C71C00'>i</font><font color='#C83300'>t</font><font color='#C84700'>s</font><font color='#C85B00'> </font><font color='#C76F00'>t</font><font color='#C78200'>o</font><font color='#C79700'>:</font><font color='#C8AE00'> </font><font color='#C7C700'>e</font><font color='#ADC800'>X</font><font color='#95C700'>t</font><font color='#7EC800'>r</font><font color='#67C800'>a</font><font color='#50C800'>g</font><font color='#38C800'>o</font><font color='#1EC800'>Z</font><font color='#00C700'> </font><font color='#00C81D'>f</font><font color='#00C838'>o</font><font color='#00C850'>r</font><font color='#00C867'> </font><font color='#00C87E'>h</font><font color='#00C895'>i</font><font color='#00C8AD'>s</font><font color='#00C8C7'> </font><font color='#00AEC8'>A</font><font color='#0097C8'>u</font><font color='#0083C8'>t</font><font color='#006FC8'>o</font><font color='#005BC8'> </font><font color='#0047C8'>S</font><font color='#0033C8'>h</font><font color='#001CC8'>i</font><font color='#0003C7'>e</font><font color='#1600C8'>l</font><font color='#2E00C7'>d</font><font color='#4600C8'> </font><font color='#5C00C8'>c</font><font color='#7300C8'>o</font><font color='#8C00C8'>d</font><font color='#A600C8'>e</font>")

PrintChat("<font color='#C80046'>C</font><font color='#C8002E'>r</font><font color='#C80014'>e</font><font color='#C80700'>d</font><font color='#C72100'>i</font><font color='#C83800'>t</font><font color='#C84D00'>s</font><font color='#C76100'> </font><font color='#C87600'>t</font><font color='#C88B00'>o</font><font color='#C8A100'>:</font><font color='#C8BA00'> </font><font color='#B9C800'>S</font><font color='#9FC800'>i</font><font color='#86C800'>d</font><font color='#6EC800'>a</font><font color='#56C800'> </font><font color='#3DC700'>f</font><font color='#22C800'>o</font><font color='#03C800'>r</font><font color='#00C81B'> </font><font color='#00C837'>t</font><font color='#00C851'>h</font><font color='#00C869'>e</font><font color='#00C881'> </font><font color='#00C899'>A</font><font color='#00C8B2'>u</font><font color='#00C1C8'>t</font><font color='#00A7C8'>o</font><font color='#0090C8'> </font><font color='#007BC7'>P</font><font color='#0066C8'>r</font><font color='#0052C8'>i</font><font color='#003DC8'>o</font><font color='#0026C8'>r</font><font color='#000DC8'>i</font><font color='#0E00C8'>t</font><font color='#2800C8'>y</font><font color='#4000C8'> </font><font color='#5800C8'>C</font><font color='#7000C8'>o</font><font color='#8900C7'>d</font><font color='#A400C8'>e</font>")

end

function OnTick()


ts:update()
if ValidTarget(ts.target) then
if not menu.ManualOverride then

if VIP_USER then
qp = TargetPredictionVIP(QRange, QSpeed, QDelay, QWidth)
predictQ = qp:GetPrediction(ts.target)
end
if menu.UseQ then UseQ() end
if menu.UseW then UseW() end
end
end
end

function UseQ()
	if VIP_USER then
		if predictQ ~= nil then
			CastSpell(_Q, predictQ.x, predictQ.z)
			CastSpell(_Q)
		end
		end
		if not VIP_USER then
		if ts.target ~= nil then
			travelDuration = (delay + GetDistance(myHero, ts.target)/QSpeed)
			ts:SetPrediction(travelDuration)
			predict = ts.nextPosition
		end
		if predict ~= nil and GetDistance(predict) < QRange then
		CastSpell(_Q, predict.x, predict.z)
		CastSpell(_Q)
		end
	end
end



function UseW()
if GetDistance(ts.target) <= 600 then
CastSpell(_W, ts.target) end
end

function OnProcessSpell(object,spell)
	if object.team ~= myHero.team and not myHero.dead and not (object.name:find("Minion_") or object.name:find("Odin")) then
		if typeshield ~= nil then
			if myHero.charName == "Lux" then range = 1075
			else range = myHero:GetSpellData(spellslot).range end
		end
		if typeheal ~= nil then healrange = myHero:GetSpellData(healslot).range end
		if typeult ~= nil then ultrange = myHero:GetSpellData(ultslot).range end
		local leesinw = myHero.charName ~= "LeeSin" or myHero:GetSpellData(_W).name == "BlindMonkWOne"
		local shieldREADY = typeshield ~= nil and myHero:CanUseSpell(spellslot) == READY and leesinw
		local healREADY = typeheal ~= nil and myHero:CanUseSpell(healslot) == READY
		local ultREADY = typeult ~= nil and myHero:CanUseSpell(ultslot) == READY
		local sbarrierREADY = sbarrier ~= nil and myHero:CanUseSpell(sbarrier) == READY
		local shealREADY = sheal ~= nil and myHero:CanUseSpell(sheal) == READY
		local lisslot = GetInventorySlotItem(3190)
		local seslot = GetInventorySlotItem(3040)
		local lisREADY = lisslot ~= nil and myHero:CanUseSpell(lisslot) == READY
		local seREADY = seslot ~= nil and myHero:CanUseSpell(seslot) == READY
		local HitFirst = false
		local shieldtarget,SLastDistance,SLastDmgPercent = nil,nil,nil
		local healtarget,HLastDistance,HLastDmgPercent = nil,nil,nil
		local ulttarget,ULastDistance,ULastDmgPercent = nil,nil,nil
		if object.type == "obj_AI_Hero" then
			spelltype, casttype = getSpellType(object, spell.name)
			if casttype == 4 or casttype == 5 then return end
			if spelltype == "Q" or spelltype == "W" or spelltype == "E" or spelltype == "R" or spelltype == "P" or spelltype == "QM" or spelltype == "WM" or spelltype == "EM" then
				HitFirst = skillShield[object.charName][spelltype]["HitFirst"]
			end
		end
		for i=1, heroManager.iCount do
			local allytarget = heroManager:GetHero(i)
			if allytarget.team == myHero.team and not allytarget.dead and allytarget.health > 0 then
				if shieldREADY and menu["teammateshield"..i] then
					if ((typeshield==1 or typeshield==2 or typeshield==5) and GetDistance(allytarget)<=range) or allytarget.isMe then
						local shieldflag, dmgpercent = shieldCheck(object,spell,allytarget,"shields")
						if shieldflag then
							if HitFirst and (SLastDistance == nil or GetDistance(allytarget,object) <= SLastDistance) then
								shieldtarget,SLastDistance = allytarget,GetDistance(allytarget,object)
							elseif not HitFirst and (SLastDmgPercent == nil or dmgpercent >= SLastDmgPercent) then
								shieldtarget,SLastDmgPercent = allytarget,dmgpercent
							end
						end
					end
				end
			

		if shieldtarget ~= nil then
			if typeshield==1 or typeshield==5 then CastSpell(spellslot,shieldtarget)
			elseif typeshield==2 or typeshield==4 then CastSpell(spellslot,shieldtarget.x,shieldtarget.z)
			elseif typeshield==3 or typeshield==6 then CastSpell(spellslot) end
		end
		if healtarget ~= nil then
			if typeheal==1 then CastSpell(healslot,healtarget)
			elseif typeheal==2 or typeheal==3 then CastSpell(healslot) end
		end
		if ulttarget ~= nil then
			if typeult==1 or typeult==3 then CastSpell(ultslot,ulttarget)
			elseif typeult==2 or typeult==4 then CastSpell(ultslot) end		
		end
	end	
end
end
end

function shieldCheck(object,spell,target,typeused)
	--local spellName,spellLevel,P1,P2 = spell.name,spell.level,spell.startPos,spell.endPos
	--local P1 = {x = object.x, y = object.y, z = object.z}
	local spellName,spellLevel,P2 = spell.name,spell.level,spell.endPos
	local configused
	if typeused == "shields" then configused = menu end
	local shieldflag = false
	local adamage = object:CalcDamage(target,object.totalDamage)
	local InfinityEdge,onhitdmg,onhittdmg,onhitspelldmg,onhitspelltdmg,muramanadmg,skilldamage,skillTypeDmg = 0,0,0,0,0,0,0,0
	local BShield,SShield,Shield,CC = false,false,false,false
	local shottype,radius,maxdistance = 0,0,0
	local hitchampion = false
	if object.type ~= "obj_AI_Hero" then
		if spellName:find("BasicAttack") then skilldamage = adamage
		elseif spellName:find("CritAttack") then skilldamage = adamage*2 end
		Shield = true
	else
		if GetInventoryHaveItem(3186,object) then onhitdmg = getDmg("KITAES",target,object) end
		if GetInventoryHaveItem(3114,object) then onhitdmg = onhitdmg+getDmg("MALADY",target,object) end
		if GetInventoryHaveItem(3091,object) then onhitdmg = onhitdmg+getDmg("WITSEND",target,object) end
		if GetInventoryHaveItem(3057,object) then onhitdmg = onhitdmg+getDmg("SHEEN",target,object) end
		if GetInventoryHaveItem(3078,object) then onhitdmg = onhitdmg+getDmg("TRINITY",target,object) end
		if GetInventoryHaveItem(3100,object) then onhitdmg = onhitdmg+getDmg("LICHBANE",target,object) end
		if GetInventoryHaveItem(3025,object) then onhitdmg = onhitdmg+getDmg("ICEBORN",target,object) end
		if GetInventoryHaveItem(3087,object) then onhitdmg = onhitdmg+getDmg("STATIKK",target,object) end
		if GetInventoryHaveItem(3153,object) then onhitdmg = onhitdmg+getDmg("RUINEDKING",target,object) end
		if GetInventoryHaveItem(3209,object) then onhittdmg = getDmg("SPIRITLIZARD",target,object) end
		if GetInventoryHaveItem(3184,object) then onhittdmg = onhittdmg+80 end
		if GetInventoryHaveItem(3042,object) then muramanadmg = getDmg("MURAMANA",target,object) end
		if spelltype == "BAttack" then
			skilldamage = (adamage+onhitdmg+muramanadmg)*1.07+onhittdmg
			Shield = true
		elseif spelltype == "CAttack" then
			if GetInventoryHaveItem(3031,object) then InfinityEdge = .5 end
			skilldamage = (adamage*(2.1+InfinityEdge)+onhitdmg+muramanadmg)*1.07+onhittdmg --fix Lethality
			Shield = true
		elseif spelltype == "Q" or spelltype == "W" or spelltype == "E" or spelltype == "R" or spelltype == "P" or spelltype == "QM" or spelltype == "WM" or spelltype == "EM" then
			if GetInventoryHaveItem(3151,object) then onhitspelldmg = getDmg("LIANDRYS",target,object) end
			if GetInventoryHaveItem(3188,object) then onhitspelldmg = getDmg("BLACKFIRE",target,object) end
			if GetInventoryHaveItem(3209,object) then onhitspelltdmg = getDmg("SPIRITLIZARD",target,object) end
			BShield = skillShield[object.charName][spelltype]["BShield"]
			SShield = skillShield[object.charName][spelltype]["SShield"]
			Shield = skillShield[object.charName][spelltype]["Shield"]
			CC = skillShield[object.charName][spelltype]["CC"]
			shottype = skillData[object.charName][spelltype]["type"]
			radius = skillData[object.charName][spelltype]["radius"]
			maxdistance = skillData[object.charName][spelltype]["maxdistance"]
			muramanadmg = skillShield[object.charName][spelltype]["Muramana"] and muramanadmg or 0
			if casttype == 1 then
				skilldamage, skillTypeDmg = getDmg(spelltype,target,object,1,spellLevel)
			elseif casttype == 2 then
				skilldamage, skillTypeDmg = getDmg(spelltype,target,object,2,spellLevel)
			elseif casttype == 3 then
				skilldamage, skillTypeDmg = getDmg(spelltype,target,object,3,spellLevel)
			end
			if skillTypeDmg == 2 then
				skilldamage = (skilldamage+adamage+onhitspelldmg+onhitdmg+muramanadmg)*1.07+onhittdmg+onhitspelltdmg
			else
				if skilldamage > 0 then skilldamage = (skilldamage+onhitspelldmg+muramanadmg)*1.07+onhitspelltdmg end
			end
		elseif spellName:find("SummonerDot") then
			skilldamage = getDmg("IGNITE",target,object)
			Shield = true
		end
	end
	if shottype == 0 then hitchampion = checkhitaoe(object, P2, 80, target, 0)
	elseif shottype == 1 then hitchampion = checkhitlinepass(object, P2, radius, maxdistance, target, configused.playerradius)
	elseif shottype == 2 then hitchampion = checkhitlinepoint(object, P2, radius, maxdistance, target, configused.playerradius)
	elseif shottype == 3 then hitchampion = checkhitaoe(object, P2, radius, maxdistance, target, configused.playerradius)
	elseif shottype == 4 then hitchampion = checkhitcone(object, P2, radius, maxdistance, target, configused.playerradius)
	elseif shottype == 5 then hitchampion = checkhitwall(object, P2, radius, maxdistance, target, configused.playerradius)
	elseif shottype == 6 then hitchampion = checkhitlinepass(object, P2, radius, maxdistance, target, configused.playerradius) or checkhitlinepass(object, Vector(object)*2-P2, radius, maxdistance, target, configused.playerradius)
	elseif shottype == 7 then hitchampion = checkhitcone(P2, object, radius, maxdistance, target, configused.playerradius)
	end
	local dmgpercent = skilldamage*100/target.health
	local dmgneeded = dmgpercent >= configused.mindmgpercent
	local hpneeded = configused.maxhppercent >= (target.health-skilldamage)*100/target.maxHealth
	if hitchampion and (configused.skillshots or shottype == 0) then
		if typeused == "shields" and ((dmgneeded and hpneeded) or (CC == 2 and configused.shieldcc) or (CC == 1 and configused.shieldslow)) then
			shieldflag = (typeshield<=4 and Shield) or (typeshield==5 and BShield) or (typeshield==6 and SShield)
		else shieldflag = (typeused == "heals" or typeused == "ult" or typeused == "barrier" or typeused == "sheals" or typeused == "items") and dmgneeded and hpneeded end
	end
	return shieldflag, dmgpercent
end

function OnDraw()
if typeshield ~= nil then
		if menu.drawcircles and not myHero.dead and (typeshield == 1 or typeshield == 2 or typeshield == 5) then
			DrawCircle(myHero.x, myHero.y, myHero.z, range, 0x19A712)
		end
		menu.mindmg = math.floor(myHero.health*menu.mindmgpercent/100)
	end

end

	

local priorityTable = {

 

    AP = {

        "Annie", "Ahri", "Akali", "Anivia", "Annie", "Brand", "Cassiopeia", "Diana", "Evelynn", "FiddleSticks", "Fizz", "Gragas", "Heimerdinger", "Karthus",

        "Kassadin", "Katarina", "Kayle", "Kennen", "Leblanc", "Lissandra", "Lux", "Malzahar", "Mordekaiser", "Morgana", "Nidalee", "Orianna",

        "Rumble", "Ryze", "Sion", "Swain", "Syndra", "Teemo", "TwistedFate", "Veigar", "Viktor", "Vladimir", "Xerath", "Ziggs", "Zyra", "MasterYi",

    },

    Support = {

        "Alistar", "Blitzcrank", "Janna", "Karma", "Leona", "Lulu", "Nami", "Nunu", "Sona", "Soraka", "Taric", "Thresh", "Zilean",

    },

 

    Tank = {

        "Amumu", "Chogath", "DrMundo", "Galio", "Hecarim", "Malphite", "Maokai", "Nasus", "Rammus", "Sejuani", "Shen", "Singed", "Skarner", "Volibear",

        "Warwick", "Yorick", "Zac",

    },

 

    AD_Carry = {

        "Ashe", "Caitlyn", "Corki", "Draven", "Ezreal", "Graves", "Jayce", "KogMaw", "MissFortune", "Pantheon", "Quinn", "Shaco", "Sivir",

        "Talon", "Tristana", "Twitch", "Urgot", "Varus", "Vayne", 

 

    },

 

    Bruiser = {

        "Darius", "Elise", "Fiora", "Gangplank", "Garen", "Irelia", "JarvanIV", "Jax", "Khazix", "LeeSin", "Nautilus", "Nocturne", "Olaf", "Poppy",

        "Renekton", "Rengar", "Riven", "Shyvana", "Trundle", "Tryndamere", "Udyr", "Vi", "MonkeyKing", "XinZhao", "Zed",

    },

 

}

 

function SetPriority(table, hero, priority)

        for i=1, #table, 1 do

                if hero.charName:find(table[i]) ~= nil then

                        TS_SetHeroPriority(priority, hero.charName)

                end

        end

end

 

function arrangePrioritys()

        for i, enemy in ipairs(GetEnemyHeroes()) do

                SetPriority(priorityTable.AD_Carry, enemy, 1)

                SetPriority(priorityTable.AP,       enemy, 2)

                SetPriority(priorityTable.Support,  enemy, 3)

                SetPriority(priorityTable.Bruiser,  enemy, 4)

                SetPriority(priorityTable.Tank,     enemy, 5)

        end

end

 

function PriorityOnLoad()

        if heroManager.iCount < 10 then

                PrintChat(" >> Too few champions to arrange priority")

        else

                TargetSelector(TARGET_LOW_HP_PRIORITY, 0)

                arrangePrioritys()

end
end


--UPDATEURL=
--HASH=83355804A81143BEBA4C6E14D53C4FCE
