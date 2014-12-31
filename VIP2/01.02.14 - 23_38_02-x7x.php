<?php exit() ?>--by x7x 89.70.161.4
if myHero.charName ~= "Orianna" then return end
local scriptReady = false
local varFirstLoad = true
local varUpdated = false -- temp fix
local versionGOE = 0.92
local nameGOE = "Orianna - Flying Balls"
local fnameGOE = "aOrianna"
local needUpdate_GOE = false
local needRun_GOE = true
local needRun_GOE_2 = true
local URL_GOE = "http://anony4bol.comoj.com/Orianna.lua"
local PATH_GOE = BOL_PATH.."Scripts\\"..fnameGOE..".lua"
local b='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'
local DownloadedTable = false
local DownloadingTable = false
function DecodeB64(data)
    data = string.gsub(data, '[^'..b..'=]', '')
    return (data:gsub('.', function(x)
        if (x == '=') then return '' end
        local r,f='',(b:find(x)-1)
        for i=6,1,-1 do r=r..(f%2^i-f%2^(i-1)>0 and '1' or '0') end
        return r;
    end):gsub('%d%d%d?%d?%d?%d?%d?%d?', function(x)
        if (#x ~= 8) then return '' end
        local c=0
        for i=1,8 do c=c+(x:sub(i,i)=='1' and 2^(8-i) or 0) end
        return string.char(c)
    end))
end
function SplitString(s, delimiter)
    result = {};
    for match in (s..delimiter):gmatch("(.-)"..delimiter) do
        table.insert(result, match);
    end
    return result;
end
local tabZ = {}
function decGOE(data)
	if data ~= nil then
		local nString = DecodeB64(tostring(data))
		--tabZ = {"spudgy", "149kg"} --local table, for now
		tabZ = SplitString(nString, "||")
		DownloadedTable = true
	end
end
function CheckVersionGOE(data)
	local onlineVerGOE = tonumber(data)
	if type(onlineVerGOE) ~= "number" then return end
	if onlineVerGOE and onlineVerGOE > versionGOE then
		print("<font color='#00BFFF'>AUTOUPDATER: There is a new version of "..nameGOE..". Don't F9 till done...</font>") 
		print("<font color='#00BFFF'>AUTOUPDATER: Current: v"..versionGOE..", New: "..onlineVerGOE.."</font>") 
		needUpdate_GOE = true 
	elseif onlineVerGOE and onlineVerGOE <= versionGOE then
		varUpdated = true
	end
end
function UpdateScriptGOE()
	
	if needRun_GOE then
		needRun_GOE = false
		if debug.getinfo(GetAsyncWebResult).what == "C" then GetAsyncWebResult("anony4bol.comoj.com", "OriannaVer.lua", CheckVersionGOE) end
	end
	
	if needRun_GOE_2 and not needRun_GOE then
		needRun_GOE_2 = false
		if debug.getinfo(GetAsyncWebResult).what == "C" then GetAsyncWebResult("anony4bol.comoj.com", "vipusers.php", decGOE) end
	end

	if needUpdate_GOE then
		needUpdate_GOE = false
		DownloadFile(URL_GOE, PATH_GOE, function()
                if FileExist(PATH_GOE) then
                    print("<font color='#00BFFF'>AUTOUPDATER: Script updated! Reload scripts to use new version!</font>")
                end
            end)
	end
end
AddTickCallback(UpdateScriptGOE)

local Prodict = ProdictManager.GetInstance()
local QProdict = nil
local ts = TargetSelector(TARGET_LESS_CAST_PRIORITY,825,DAMAGE_MAGICAL,false)
local Enemies = {}
local Allies = {}
local LastAATarget = nil
local NextAATick = 0
local EndOfWindupTick = 0
local AutoAttackRange = 550
local CanQ = false
local CanW = false
local CanE = false
local CanR = false
local CanIgnite = false
local RbyScript = false
local waittxt = {}
local Ball = myHero
local BallMoving = false
local ASDownloading = false
local ASdownloaded = false
local ASlastload = 0
function OnUnload()
	if scriptReady then
		PrintChat("<font color='#00BFFF'>"..nameGOE.." v"..versionGOE.." unloaded!</font>")
	end
end
function OnTick()
	if varFirstLoad == true and varUpdated == true then
		local v1 = debug.getinfo(GetUser)
		local v2 = v1.what
		if v2 == "C" then
			local v3 = debug.getinfo(debug.getinfo)
			local v4 = v3.what
			if v4 == "C" then
				if DownloadedTable then
					for i, x in pairs(tabZ) do
						if tostring(x) == GetUser() then
							QProdict = Prodict:AddProdictionObject(_Q, 1700, 1200, 0.255, 80)
							Menu = scriptConfig(nameGOE.." v"..versionGOE, "aoriannalikeabaws")
							ts.name = "Orianna TS"
							Menu:addTS(ts)
							Menu:addSubMenu("General information", "InfoMenu")
							Menu.InfoMenu:addParam("Information1", "          "..nameGOE.." v"..versionGOE, SCRIPT_PARAM_INFO, "")
							Menu.InfoMenu:permaShow("Information1")
							Menu.InfoMenu:addParam("Information2", "Huge thanks for donation and support!", SCRIPT_PARAM_INFO, "")
							Menu.InfoMenu:addParam("Information3", "Enjoy script and report bugs on forums!", SCRIPT_PARAM_INFO, "")
							Menu:addSubMenu("Combo settings", "ComboMenu")
							Menu.ComboMenu:addParam("ComboKey","Combo hotkey", SCRIPT_PARAM_ONKEYDOWN, false, 32)
							Menu.ComboMenu:permaShow("ComboKey")
							Menu.ComboMenu:addParam("useAA","Orbwalking", SCRIPT_PARAM_ONOFF, true)
							Menu.ComboMenu:addParam("useQ","Use Q automaticly", SCRIPT_PARAM_ONOFF, true)
							Menu.ComboMenu:addParam("useMEC","Use MEC for Q", SCRIPT_PARAM_ONOFF, true)
							Menu.ComboMenu:addParam("useW","Use W automaticly", SCRIPT_PARAM_ONOFF, true)
							Menu.ComboMenu:addParam("useE","Use E automaticly", SCRIPT_PARAM_ONOFF, true)
							Menu.ComboMenu:addParam("useR","Use R automaticly if:", SCRIPT_PARAM_ONOFF, true)
							Menu.ComboMenu:addParam("RcatchAmount","- it can catch X enemies:", SCRIPT_PARAM_SLICE, 2, 0, 5, 0)
							Menu.ComboMenu:addParam("RkillAmount","- it can kill X enemies:", SCRIPT_PARAM_SLICE, 1, 0, 5, 0)
							Menu:addSubMenu("Harras settings", "HarrasMenu")
							Menu.HarrasMenu:addParam("HarrasKey","Harras key", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("A"))
							Menu.HarrasMenu:addParam("useQ","Use Q automaticly", SCRIPT_PARAM_ONOFF, true)
							Menu.HarrasMenu:addParam("useW","Use W automaticly", SCRIPT_PARAM_ONOFF, true)
							Menu.HarrasMenu:addParam("useE","Use E automaticly", SCRIPT_PARAM_ONOFF, false)
							Menu.HarrasMenu:permaShow("HarrasKey")
							Menu:addSubMenu("Ultimate helper settings", "UltHelper")
							Menu.UltHelper:addParam("Inf", "This func will use Q to get", SCRIPT_PARAM_INFO, "")
							Menu.UltHelper:addParam("Inf1", "possibly best ball placement", SCRIPT_PARAM_INFO, "")
							Menu.UltHelper:addParam("Inf1", "and it will ult after it.", SCRIPT_PARAM_INFO, "")
							Menu.UltHelper:addParam("Inf1", "Casting ultimate will turn func on.", SCRIPT_PARAM_INFO, "")
							Menu.UltHelper:addParam("enabled","Override R cast", SCRIPT_PARAM_ONOFF, true)
							Menu.UltHelper:addParam("UltKey","Casting ultimate (AUTO)", SCRIPT_PARAM_ONOFF, false)
							Menu.UltHelper:addParam("info1", "Conditions: (Optional)", SCRIPT_PARAM_INFO, "")
							Menu.UltHelper:addParam("RcatchAmount","- it can catch X enemies:", SCRIPT_PARAM_SLICE, 1, 0, 5, 0)
							Menu.UltHelper:addParam("RkillAmount","- it can kill X enemies:", SCRIPT_PARAM_SLICE, 1, 0, 5, 0)
							Menu.UltHelper:permaShow("UltKey")
							Menu:addSubMenu("Drawer settings", "DrawMenu")
							Menu.DrawMenu:addParam("drawReturnrange","Draw ball return range", SCRIPT_PARAM_ONOFF, true)
							Menu.DrawMenu:addParam("drawQrange","Draw Q range", SCRIPT_PARAM_ONOFF, true)
							Menu.DrawMenu:addParam("drawWrange","Draw W range", SCRIPT_PARAM_ONOFF, false)
							Menu.DrawMenu:addParam("drawErange","Draw E range", SCRIPT_PARAM_ONOFF, false)
							Menu.DrawMenu:addParam("drawRrange","Draw R range", SCRIPT_PARAM_ONOFF, true)
							Menu.DrawMenu:addParam("drawHPtext","Draw damage output info", SCRIPT_PARAM_ONOFF, true)
							Menu:addSubMenu("Shielding settings", "ShdMenu")
							Menu.ShdMenu:addParam("download","Download eXtragoZ AutoShield", SCRIPT_PARAM_ONOFF, true)
							Menu.ShdMenu:addParam("loadscript","Load eXtragoZ AutoShield", SCRIPT_PARAM_ONOFF, true)
							--[[Menu.ShdMenu:addParam("info1","This AS shields only AA, and", SCRIPT_PARAM_INFO, "")
							Menu.ShdMenu:addParam("info2","targeted spells. Its recommended", SCRIPT_PARAM_INFO, "")
							Menu.ShdMenu:addParam("info3","to use AutoShield by eXtragoZ!", SCRIPT_PARAM_INFO, "")
							Menu.ShdMenu:addParam("info4","You can find it in BOL Mods :)", SCRIPT_PARAM_INFO, "")
							Menu.ShdMenu:addParam("enabled","Use built-in AutoShield", SCRIPT_PARAM_ONOFF, true)
							Menu.ShdMenu:addParam("minDmg","Min. DMG to use shield", SCRIPT_PARAM_SLICE, 100, 0, 1500, 0)]]
							Menu:addSubMenu("Other settings", "OtherMenu")
							Menu.OtherMenu:addParam("blockNull","Block R if it doesnt hit anyone", SCRIPT_PARAM_ONOFF, true)
							Menu.OtherMenu:addParam("info1", "Use R if:", SCRIPT_PARAM_INFO, "")
							Menu.OtherMenu:addParam("RcatchAmount","- it can catch X enemies:", SCRIPT_PARAM_SLICE, 4, 0, 5, 0)
							Menu.OtherMenu:addParam("RkillAmount","- it can kill X enemies:", SCRIPT_PARAM_SLICE, 2, 0, 5, 0)
							Menu.OtherMenu:addParam("info2", "This works without SBTW key!", SCRIPT_PARAM_INFO, "")
							local ordertxt = 1
							for i=1, heroManager.iCount do
								waittxt[i] = ordertxt
								ordertxt = ordertxt+1
								local EnemyHero = heroManager:getHero(i)
								if EnemyHero ~= nil and EnemyHero.team == TEAM_ENEMY then table.insert(Enemies, EnemyHero) end
								if EnemyHero ~= nil and myHero.team == EnemyHero.team then table.insert(Allies, EnemyHero) end
							end
							PrintChat("<font color='#00BFFF'>"..nameGOE.." v"..versionGOE.." loaded!</font>")
							scriptReady = true
							varFirstLoad = false
							--[[for i, Ally in pairs(Allies) do
								Menu.ShdMenu:addParam("Enabled"..Ally.charName, "Use E to shield "..Ally.charName, SCRIPT_PARAM_ONOFF, true)
							end]]
						end
					end
				end
			end
		end
	elseif scriptReady and not varFirstLoad and varUpdated then
		-- AutoShield section! --
		local ASpath = BOL_PATH.."Scripts\\".."AutoShield(Orianna)"..".lua"
		if Menu.ShdMenu.download and not ASDownloading then
			if not FileExist(ASpath) then
				ASDownloading = true
				DownloadFile("http://anony4bol.comoj.com/AS.lua", ASpath, function()
					if FileExist(ASpath) then
						ASdownloaded = true
					end
				end)
			else
				ASdownloaded = true
			end
		end
		if Menu.ShdMenu.loadscript and ASdownloaded and GetTickCount()-ASlastload > 2500 then
			if _G.AutoShieldLoaded ~= true then
				LoadScript("AutoShield(Orianna)"..".lua")
				ASlastload = GetTickCount()
			end
		end
		-- End of AS section!  --
		ts:update()
		if Ball == nil or not Ball.valid then Ball = myHero end
		CanQ = (myHero:CanUseSpell(_Q) == READY)
		CanW = (myHero:CanUseSpell(_W) == READY)
		CanE = (myHero:CanUseSpell(_E) == READY)
		CanR = (myHero:CanUseSpell(_R) == READY)
		CanIgnite = (iSlot ~= nil and myHero:CanUseSpell(iSlot) == READY)
		--Combo Stuff
		if ts.target ~= nil and not BallMoving and Menu.ComboMenu.ComboKey and not Menu.UltHelper.UltKey then
			if Menu.ComboMenu.useQ and CanQ then
				if CountEnemyHeroInRange(1025) >= 2 and Menu.ComboMenu.useMEC and CanR then
					local Pos = GetAoESpellPosition(250, ts.target, 100)
					if Pos ~= nil and GetDistance(Pos) < 825 then
						CastSpell(_Q, Pos.x, Pos.z)
					else
						CastQ(ts.target)
					end
				else
					CastQ(ts.target)
				end
			end
			if Menu.ComboMenu.useW and CanW then
				if GetDistance(Ball, ts.target) < 225 then CastSpell(_W) end
			end
			if Menu.ComboMenu.useE and CanE then
				local CanHit = checkhitlinepass(Ball, myHero, 35, 1100, ts.target, GetDistance(ts.target, ts.target.minBBox)/2)
				if CanHit then CastSpell(_E, myHero) end
			end
			if Menu.ComboMenu.useR and CanR then
				if Menu.ComboMenu.RcatchAmount ~= 0 then
					local n = 0
					for i, Enemy in pairs(Enemies) do
						if Enemy ~= nil and not Enemy.dead and Enemy.visible and GetDistance(Ball, Enemy) < 400 then n = n + 1 end
					end
					if n >= Menu.ComboMenu.RcatchAmount then
						RbyScript = true
						CastSpell(_R)
					end
				end
				if Menu.ComboMenu.RkillAmount ~= 0 then
					local n = 0
					for i, Enemy in pairs(Enemies) do
						if Enemy ~= nil and not Enemy.dead and Enemy.visible and GetDistance(Ball, Enemy) < 400 and getDmg("R", Enemy, myHero) > Enemy.health then n = n + 1 end
					end
					if n >= Menu.ComboMenu.RkillAmount then
						RbyScript = true
						CastSpell(_R)
					end
				end
			end
		end
		if Menu.ComboMenu.ComboKey and Menu.ComboMenu.useAA then OrbwalkTarget(ts.target) end
		--UltHelper Stuff
		if Menu.UltHelper.enabled and Menu.UltHelper.UltKey and not BallMoving then
			if ts.target ~= nil and CanQ then
				local CastPos = GetAoESpellPosition(400, ts.target, 50)
				local CastPosCatch = 0
				local BestAlly = nil
				local BestAllyCatch = 0
				local NearestAlly = Ball
				for i, obj in pairs(Allies) do
					if GetDistance(CastPos, obj) < GetDistance(CastPos, NearestAlly) then
						NearestAlly = obj
					end
					local ObjCatch = 0
					for x, eobj in pairs(Enemies) do
						if eobj.valid and eobj.visible and not eobj.dead and GetDistance(eobj, obj) < 400 then
							ObjCatch = ObjCatch + 1
						end
					end
					if ObjCatch > BestAllyCatch then
						BestAlly = obj
						BestAllyCatch = ObjCatch
					end
				end
				for i, eobj in pairs(Enemies) do
					if eobj.valid and eobj.visible and not eobj.dead and GetDistance(eobj, CastPos) then
						CastPosCatch = CastPosCatch + 1
					end
				end
				if BestAllyCatch >= CatchPosCatch and CanE then
					CastSpell(_E, BestAlly)
				else
					if NearestAlly ~= Ball and NearestAlly ~= myHero and CanE then
						CastSpell(_E, NearestAlly)
						CastSpell(_Q, CastPos.x, CastPos.z)
					else
						CastSpell(_Q, CastPos.x, CastPos.z)
					end
				end
			end
			if Menu.UltHelper.RcatchAmount ~= 0 then
				local n = 0
				for i, Enemy in pairs(Enemies) do
					if Enemy ~= nil and not Enemy.dead and Enemy.visible and GetDistance(Ball, Enemy) < 400 then n = n + 1 end
				end
				if n >= Menu.UltHelper.RcatchAmount then
					RbyScript = true
					CastSpell(_R)
				end
			end
			if Menu.UltHelper.RkillAmount ~= 0 then
				local n = 0
				for i, Enemy in pairs(Enemies) do
					if Enemy ~= nil and not Enemy.dead and Enemy.visible and GetDistance(Ball, Enemy) < 400 and getDmg("R", Enemy, myHero) > Enemy.health then n = n + 1 end
				end
				if n >= Menu.UltHelper.RkillAmount then
					RbyScript = true
					CastSpell(_R)
				end
			end
		end
		--AuHRS Stuff
		if ts.target ~= nil and not BallMoving and Menu.HarrasMenu.HarrasKey and not Menu.UltHelper.UltKey then
			if Menu.HarrasMenu.useQ and CanQ then CastQ(ts.target) end
			if Menu.HarrasMenu.useW and CanW then
				if GetDistance(Ball, ts.target) < 225 then CastSpell(_W) end
			end
			if Menu.HarrasMenu.useE and CanE then
				local CanHit = checkhitlinepass(Ball, myHero, 35, 1100, ts.target, GetDistance(ts.target, ts.target.minBBox)/2)
				if CanHit then CastSpell(_E, myHero) end
			end
		end
		--Other Stuff
		if BallMoving == false and CanR then
			if Menu.OtherMenu.RcatchAmount ~= 0 then
				local n = 0
				for i, Enemy in pairs(Enemies) do
					if Enemy ~= nil and not Enemy.dead and Enemy.visible and GetDistance(Ball, Enemy) < 400 then n = n + 1 end
				end
				if n >= Menu.OtherMenu.RcatchAmount then
					RbyScript = true
					CastSpell(_R)
					--PrintChat("Ult")
				end
				--PrintChat("UltWillCatch: "..n)
			end
			if Menu.OtherMenu.RkillAmount ~= 0 then
				local n = 0
				for i, Enemy in pairs(Enemies) do
					if Enemy ~= nil and not Enemy.dead and Enemy.visible and GetDistance(Ball, Enemy) < 400 and getDmg("R", Enemy, myHero) >= Enemy.health then n = n + 1 end
				end
				if n >= Menu.OtherMenu.RkillAmount then
					RbyScript = true
					CastSpell(_R)
				end
			end
		end
	end
end
function OrbwalkTarget(Target)
	if Target == nil then 
		MoveTo(mousePos.x, mousePos.z)
	else
		if CanShoot() and GetDistance(Target) < 550 then
			Attack(Target)
		else
			if CanMove() then MoveTo(mousePos.x, mousePos.z) end
		end
	end
end
function CanShoot()
	if GetTickCount() >= NextAATick then return true else return false end
end
function CanMove()
	if GetTickCount() >= EndOfWindupTick then return true else return false end
end
function MoveTo(toX, toZ)
	local FM = Point(myHero.x, myHero.z)-(Point(myHero.x, myHero.z)-Point(toX, toZ)):normalized()*250
	Packet('S_MOVE', {type = 2, x=FM.x, y=FM.y}):send()
end
function Attack(target)
	if target ~= nil and target.valid and not target.dead and GetDistance(target) <= 550 then
		myHero:Attack(target)
	end
end
function OnSendPacket(p)
	local packet = Packet(p)
	if packet:get('name') == 'S_CAST' and Menu.OtherMenu.blockNull then
		local SpellID = packet:get('spellId')
		if SpellID == SPELL_4 then
			if Menu.UltHelper.enabled then
				if not RbyScript then
					if Menu.UltHelper.UltKey then
						Menu.UltHelper.UltKey = false
					else
						Menu.UltHelper.UltKey = true
					end
					p:Block()
				else
					RbyScript = false
					--PrintChat("RbyScript")
				end
			end
			local ValidTargets = 0
			for i, Enemy in pairs(Enemies) do
				if Enemy ~= nil and not Enemy.dead and Enemy.visible and GetDistance(Ball, Enemy) < 400 then ValidTargets = ValidTargets + 1 end
			end
			if ValidTargets == 0 then p:Block() end
		end
	end
end
function CastQ(Target)
	if Menu.ComboMenu.useQ and Target ~= nil and not Target.dead then
		local pos = QProdict:GetPrediction(Target, Ball)
		if pos ~= nil and GetDistance(pos) < 825 then
			CastSpell(_Q, pos.x, pos.z)
		end
	end
end
function round(num, idp)
  return tonumber(string.format("%." .. (idp or 0) .. "f", num))
end
function OnCreateObj(obj)
	if obj == nil or obj.name == nil then return end
	if (obj.name:find("yomu_ring_green")) then
		Ball = obj
		BallMoving = false
	end
	if (obj.name:find("Orianna_Ball_Flash_Reverse")) then
		Ball = myHero
		BallMoving = false
	end
end
function OnProcessSpell(unit, spell)
	--if unit.isMe then PrintChat(spell.name) end
	if scriptReady then--and CanE and Menu.ShdMenu.enabled then
		if unit.isMe and spell.name == "OrianaRedactCommand" then
			Ball = spell.target
			BallMoving = false
		end
		if unit.isMe and spell.name == "OrianaIzunaCommand" then
			BallMoving = true
		end
		if unit.isMe and (spell.name:lower():find("attack")) then
			NextAATick = (spell.animationTime*1000)+GetTickCount() - GetLatency()/2
			EndOfWindupTick = (spell.windUpTime*1000)+GetTickCount() - GetLatency()/2
		end
		if unit.isMe and spell.name == "OrianaDetonateCommand" then
			if Menu.UltHelper.enabled and Menu.UltHelper.UltKey then Menu.UltHelper.UltKey = false end 
		end
		--[[if spell.target ~= nil then
			isEnemy = false
			for _, en in pairs(Enemies) do
				if unit.networkID == en.networkID then
					isEnemy = true
					--PrintChat("MaybeSHD1")
				end
			end
			isAllyTarget = false
			for _, en in pairs(Allies) do
				if spell.target.networkID == en.networkID then
					isAllyTarget = true
					--PrintChat("MaybeSHD2")
				end
			end
			if isEnemy and isAllyTarget and GetDistance(spell.target) < 1100 then
				--PrintChat("MaybeSHD")
				local SpellType = getSpellType(unit, spell.name)
				local ShotType = 0
				local DMG = 0
				if SpellType == "BAttack" or SpellType == "CAttack" then
					--PrintChat("Btk")
					if SpellType == "CAttack" then
						DMG = getDmg("AD", spell.target, unit)*2
					else
						DMG = getDmg("AD", spell.target, unit)*1
					end
				elseif SpellType == "Q" or SpellType == "W" or SpellType == "E" or SpellType == "R" or SpellType == "P" or SpellType == "QM" or SpellType == "WM" or SpellType == "EM" then
					if ShotType == 0 then
						DMG = getDmg(SpellType, spell.target, unit)
					else
						PrintChat("WrongShottype")
					end
				end
				if DMG > Menu.ShdMenu.minDmg then
					CastSpell(_E, spell.target)
					--PrintChat("SHIELDING")
				end
			end
		end]]
	end
end
function OnDraw()
	if scriptReady then
		if Menu.DrawMenu.drawReturnrange and Ball ~= myHero then DrawCircle(Ball.x, Ball.y, Ball.z, 1325, RGB(0,191,255)) end
		if Menu.DrawMenu.drawQrange then DrawCircle(myHero.x, myHero.y, myHero.z, 825, RGB(0,191,255)) end
		if Menu.DrawMenu.drawWrange and Ball ~= nil then DrawCircle(Ball.x, Ball.y, Ball.z, 250, RGB(0,191,255)) end
		if Menu.DrawMenu.drawErange then DrawCircle(myHero.x, myHero.y, myHero.z, 1100, RGB(0,191,255)) end
		if Menu.DrawMenu.drawRrange and Ball ~= nil and not BallMoving and CanR then DrawCircle(Ball.x, Ball.y, Ball.z, 400, RGB(255,0,255)) end
		if Menu.DrawMenu.drawHPtext then
			for n, DrawTarget in pairs(Enemies) do
				if DrawTarget ~= nil and DrawTarget.valid and not DrawTarget.dead and DrawTarget.team == TEAM_ENEMY then
					local Qdmg = 0
					local Wdmg = 0
					local Edmg = 0
					local Rdmg = 0
					local IGNdmg = 0
					if CanQ then Qdmg = getDmg("Q", DrawTarget, myHero)*1 end
					if CanW then Wdmg = getDmg("W", DrawTarget, myHero)*1 end
					if CanE then Edmg = getDmg("E", DrawTarget, myHero)*1 end
					if CanR then Rdmg = getDmg("R", DrawTarget, myHero)*1 end
					if CanIgnite then IGNdmg = getDmg("IGNITE", DrawTarget, myHero) end
					local TotalDMG = Qdmg+Wdmg+Edmg+Rdmg+IGNdmg
					if Qdmg+Wdmg+Edmg > DrawTarget.health then
						if waittxt[n] == 1 then 
							PrintFloatText(DrawTarget, 0, "MURDER HIM")
							waittxt[n] = 10
						else
							waittxt[n] = waittxt[n]-1
						end
					elseif TotalDMG > DrawTarget.health then
						if waittxt[n] == 1 then 
							PrintFloatText(DrawTarget, 0, "Killable")
							waittxt[n] = 10
						else
							waittxt[n] = waittxt[n]-1
						end
					else
						if waittxt[n] == 1 then 
							PrintFloatText(DrawTarget, 0, "Not killable")
							waittxt[n] = 10
						else
							waittxt[n] = waittxt[n]-1
						end
					end
				end
			end
		end
	end
end

--[[
        AoESkillshotPosition 2.0 by monogato
       
        GetAoESpellPosition(radius, main_target, [delay]) returns best position in order to catch as many enemies as possible with your AoE skillshot, making sure you get the main target.
        Note: You can optionally add delay in ms for prediction (VIP if avaliable, normal else).
]]
 
function GetCenter(points)
        local sum_x = 0
        local sum_z = 0
       
        for i = 1, #points do
                sum_x = sum_x + points[i].x
                sum_z = sum_z + points[i].z
        end
       
        local center = {x = sum_x / #points, y = 0, z = sum_z / #points}
       
        return center
end
 
function ContainsThemAll(circle, points)
        local radius_sqr = circle.radius*circle.radius
        local contains_them_all = true
        local i = 1
       
        while contains_them_all and i <= #points do
                contains_them_all = GetDistanceSqr(points[i], circle.center) <= radius_sqr
                i = i + 1
        end
       
        return contains_them_all
end
 
-- The first element (which is gonna be main_target) is untouchable.
function FarthestFromPositionIndex(points, position)
        local index = 2
        local actual_dist_sqr
        local max_dist_sqr = GetDistanceSqr(points[index], position)
       
        for i = 3, #points do
                actual_dist_sqr = GetDistanceSqr(points[i], position)
                if actual_dist_sqr > max_dist_sqr then
                        index = i
                        max_dist_sqr = actual_dist_sqr
                end
        end
       
        return index
end
 
function RemoveWorst(targets, position)
        local worst_target = FarthestFromPositionIndex(targets, position)
       
        table.remove(targets, worst_target)
       
        return targets
end
 
function GetInitialTargets(radius, main_target)
        local targets = {main_target}
        local diameter_sqr = 4 * radius * radius
       
        for i=1, heroManager.iCount do
                target = heroManager:GetHero(i)
                if target.networkID ~= main_target.networkID and ValidTarget(target) and GetDistanceSqr(main_target, target) < diameter_sqr then table.insert(targets, target) end
        end
       
        return targets
end
 
function GetPredictedInitialTargets(radius, main_target, delay)
        if VIP_USER and not vip_target_predictor then vip_target_predictor = TargetPredictionVIP(nil, nil, delay/1000) end
        local predicted_main_target = VIP_USER and vip_target_predictor:GetPrediction(main_target) or GetPredictionPos(main_target, delay)
        local predicted_targets = {predicted_main_target}
        local diameter_sqr = 4 * radius * radius
       
        for i=1, heroManager.iCount do
                target = heroManager:GetHero(i)
                if ValidTarget(target) then
                        predicted_target = VIP_USER and vip_target_predictor:GetPrediction(target) or GetPredictionPos(target, delay)
                        if target.networkID ~= main_target.networkID and GetDistanceSqr(predicted_main_target, predicted_target) < diameter_sqr then table.insert(predicted_targets, predicted_target) end
                end
        end
       
        return predicted_targets
end
 
-- I don´t need range since main_target is gonna be close enough. You can add it if you do.
function GetAoESpellPosition(radius, main_target, delay)
        local targets = delay and GetPredictedInitialTargets(radius, main_target, delay) or GetInitialTargets(radius, main_target)
        local position = GetCenter(targets)
        local best_pos_found = true
        local circle = Circle(position, radius)
        circle.center = position
       
        if #targets > 2 then best_pos_found = ContainsThemAll(circle, targets) end
       
        while not best_pos_found do
                targets = RemoveWorst(targets, position)
                position = GetCenter(targets)
                circle.center = position
                best_pos_found = ContainsThemAll(circle, targets)
        end
       
        return position
end