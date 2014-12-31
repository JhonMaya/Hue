<?php exit() ?>--by extragoz 190.225.172.250
--[[
	Cooldown Checker 1.6
		by eXtragoZ
		
	Features:
		- Shift + click to move the display
		- You can disable any display
		- Shift to see the ingame timer
		- On champion mode
		- You can select only show summoners or ultimate for display and on champion mode
		- Will only show abilites on CD by default, you can see all if you cange it on menu or press shift
]]
local xtext = 50
local xtext2 = 890
local ytext = 45
local stext = 15
local colorGreen = RGBA(0,255,0,255)
local colorRed = RGBA(255,0,0,255)
local textPos = {allies = {}, enemies = {}, me = {x = xtext, y = ytext+stext*30}}
local moveui = {allies = {}, enemies = {}, me = false}
local referencei = {}
local referenceallyi = {}
local stateQ,stateW,stateE,stateR,stateS1,stateS2 = {},{},{},{},{},{}
local UIHK = 16 --shift
local txtNL = "NL"
local spritesTable = {}
local textPosFile = LIB_PATH.."CooldownChecker.txt"
function writeConfigstextPos()
	local file = io.open(textPosFile, "w")
	if file and textPos.allies[1] ~= nil then
		textlist = "return {allies = {"
		for i=1,#textPos.allies do
			textlist = textlist.."{x = "..textPos.allies[i].x..", y = "..textPos.allies[i].y.."},"
		end
		textlist = textlist.."}, enemies = {"
		for i=1,#textPos.enemies do
			textlist = textlist.."{x = "..textPos.enemies[i].x..", y = "..textPos.enemies[i].y.."},"
		end
		textlist = textlist.."}, me = {x = "..textPos.me.x..", y = "..textPos.me.y.."}}"
		file:write(textlist)
		file:close()
	end
end
function OnLoad()
	CDCConfig = scriptConfig("Cooldown Checker 1.6", "CooldownChecker")
	CDCConfig:addSubMenu("Allies", "Allies")
	CDCConfig:addSubMenu("Enemies", "Enemies")
	CDCConfig:addParam("ShowMyCD", "Display CD of "..myHero.charName.." (me)", SCRIPT_PARAM_ONOFF, true)
	CDCConfig:addParam("ShowMyCD2", "CD on "..myHero.charName.." (me)", SCRIPT_PARAM_ONOFF, true)
	CDCConfig:addParam("BLANKSPACE", "-------------------------------", SCRIPT_PARAM_INFO, "")
	CDCConfig:addParam("ShowRS1S2d", "Show only R, S1 and S2 on display", SCRIPT_PARAM_ONOFF, false)
	CDCConfig:addParam("ShowRS1S2c", "Show only R, S1 and S2 on champion", SCRIPT_PARAM_ONOFF, false)
	CDCConfig:addParam("ShowOnCD", "Show only on CD", SCRIPT_PARAM_ONOFF, true)
	CDCConfig:addParam("sAlpha", "Sprites opacity", SCRIPT_PARAM_SLICE, 255, 0, 255, 0)
	CDCConfig:addParam("bAlpha", "Black background opacity", SCRIPT_PARAM_SLICE, 155, 0, 255, 0)
	if FileExist(textPosFile) then
		textPos = dofile(textPosFile)
		if not textPos.me then
			textPos = {allies = {}, enemies = {}, me = {x = xtext, y = ytext+stext*30}}
			for i = 1 , 5 do
				textPos.allies[i], textPos.enemies[i] = {x = xtext, y = ytext+stext*(5*i-5)}, {x = xtext2, y = ytext+stext*(5*i-5)}
			end
		end
	else
		for i = 1 , 5 do
			textPos.allies[i], textPos.enemies[i] = {x = xtext, y = ytext+stext*(5*i-5)}, {x = xtext2, y = ytext+stext*(5*i-5)}
		end
	end
	local posally,posenemy = 1,1
	for i=1, heroManager.iCount do
		local champion = heroManager:GetHero(i)
		local championally = champion.team == myHero.team
		if champion.isMe then
		elseif championally then
			CDCConfig.Allies:addParam("ShowCD"..posally, "Display CD of "..champion.charName, SCRIPT_PARAM_ONOFF, true)
			CDCConfig.Allies:addParam("ShowCD2"..posally, "CD on "..champion.charName, SCRIPT_PARAM_ONOFF, true)
		else
			CDCConfig.Enemies:addParam("ShowCD"..posenemy, "Display CD of "..champion.charName, SCRIPT_PARAM_ONOFF, true)
			CDCConfig.Enemies:addParam("ShowCD2"..posenemy, "CD on "..champion.charName, SCRIPT_PARAM_ONOFF, true)
		end
		stateQ[i],stateW[i],stateE[i],stateR[i],stateS1[i],stateS2[i] = "","","","","",""
		if champion.isMe then
			moveui.me = false
			referencei[i] = 1
			referenceallyi[i] = true
		elseif championally then
			moveui.allies[posally] = false
			referencei[i] = posally
			referenceallyi[i] = true
			posally = posally + 1
		else
			moveui.enemies[posenemy] = false
			referencei[i] = posenemy
			referenceallyi[i] = false
			posenemy = posenemy + 1
		end
		spritesTable[i] = {}
		spritesTable[i]["Champion"] = GetSpriteCC(champion.charName.."_Square_0.png",1)
		spritesTable[i]["Champion"]:SetScale(1/8,1/8)
		spritesTable[i]["Q"] = GetSpriteCC(champion.charName.."_Q.png")
		spritesTable[i]["Q"]:SetScale(1/4,1/4)
		spritesTable[i]["W"] = GetSpriteCC(champion.charName.."_W.png")
		spritesTable[i]["W"]:SetScale(1/4,1/4)
		spritesTable[i]["E"] = GetSpriteCC(champion.charName.."_E.png")
		spritesTable[i]["E"]:SetScale(1/4,1/4)
		spritesTable[i]["R"] = GetSpriteCC(champion.charName.."_R.png")
		spritesTable[i]["R"]:SetScale(1/4,1/4)
	end
	spritesTable["SummonerBarrier"] = GetSpriteCC("SummonerBarrier.png")
	spritesTable["SummonerBarrier"]:SetScale(1/4,1/4)
	spritesTable["SummonerBoost"] = GetSpriteCC("SummonerBoost.png")
	spritesTable["SummonerBoost"]:SetScale(1/4,1/4)
	spritesTable["SummonerClairvoyance"] = GetSpriteCC("SummonerClairvoyance.png")
	spritesTable["SummonerClairvoyance"]:SetScale(1/4,1/4)
	spritesTable["SummonerDot"] = GetSpriteCC("SummonerDot.png")
	spritesTable["SummonerDot"]:SetScale(1/4,1/4)
	spritesTable["SummonerExhaust"] = GetSpriteCC("SummonerExhaust.png")
	spritesTable["SummonerExhaust"]:SetScale(1/4,1/4)
	spritesTable["SummonerFlash"] = GetSpriteCC("SummonerFlash.png")
	spritesTable["SummonerFlash"]:SetScale(1/4,1/4)
	spritesTable["SummonerOdinGarrison"] = GetSpriteCC("SummonerOdinGarrison.png")
	spritesTable["SummonerOdinGarrison"]:SetScale(1/4,1/4)
	spritesTable["SummonerHaste"] = GetSpriteCC("SummonerHaste.png")
	spritesTable["SummonerHaste"]:SetScale(1/4,1/4)
	spritesTable["SummonerHeal"] = GetSpriteCC("SummonerHeal.png")
	spritesTable["SummonerHeal"]:SetScale(1/4,1/4)
	spritesTable["SummonerMana"] = GetSpriteCC("SummonerMana.png")
	spritesTable["SummonerMana"]:SetScale(1/4,1/4)
	spritesTable["SummonerRevive"] = GetSpriteCC("SummonerRevive.png")
	spritesTable["SummonerRevive"]:SetScale(1/4,1/4)
	spritesTable["SummonerSmite"] = GetSpriteCC("SummonerSmite.png")
	spritesTable["SummonerSmite"]:SetScale(1/4,1/4)
	spritesTable["SummonerTeleport"] = GetSpriteCC("SummonerTeleport.png")
	spritesTable["SummonerTeleport"]:SetScale(1/4,1/4)
	PrintChat(" >> Cooldown Checker 1.6 loaded!")
end
function GetHPBarPos(champion)
	local barData = champion.isMe and GetSelfBarData() or (champion.team == myHero.team and GetFriendlyBarData() or GetEnemyBarData())
	local barPos = GetUnitHPBarPos(champion)
	local barPosOffset = GetUnitHPBarOffset(champion)
	barPos.x = math.floor(barPos.x + (barPosOffset.x - 0.5 + barData.PercentageOffset.x) * 169)
	barPos.y = math.floor(barPos.y + (barPosOffset.y - 0.5 + barData.PercentageOffset.y) * 53) --47 -- 54 / 22 --53 / 11
	return {x = barPos.x + 28, y = barPos.y + 17}
end
function OnTick()
	for i=1, heroManager.iCount do
		local champion = heroManager:GetHero(i)
		local QREADY = champion:CanUseSpell(_Q) == (champion.isMe and READY or 3)
		local WREADY = champion:CanUseSpell(_W) == (champion.isMe and READY or 3)
		local EREADY = champion:CanUseSpell(_E) == (champion.isMe and READY or 3)
		local RREADY = champion:CanUseSpell(_R) == (champion.isMe and READY or 3)
		local S1READY = champion:CanUseSpell(SUMMONER_1) == (champion.isMe and READY or 3)
		local S2READY = champion:CanUseSpell(SUMMONER_2) == (champion.isMe and READY or 3)
		local QCD = champion:GetSpellData(_Q).currentCd
		local WCD = champion:GetSpellData(_W).currentCd
		local ECD = champion:GetSpellData(_E).currentCd
		local RCD = champion:GetSpellData(_R).currentCd
		local S1CD = champion:GetSpellData(SUMMONER_1).currentCd
		local S2CD = champion:GetSpellData(SUMMONER_2).currentCd
		if champion:CanUseSpell(_Q) == NOTLEARNED then
			stateQ[i] = txtNL
		elseif QCD == 0 then
			stateQ[i] = 0
		else
			if IsKeyDown(UIHK) then
				stateQ[i] = TimerText(QCD+GetInGameTimer())
			else
				stateQ[i] = string.format("%.0f", QCD)
			end
		end
		if champion:CanUseSpell(_W) == NOTLEARNED then
			stateW[i] = txtNL
		elseif WCD == 0 then
			stateW[i] = 0
		else
			if IsKeyDown(UIHK) then
				stateW[i] = TimerText(WCD+GetInGameTimer())
			else
				stateW[i] = string.format("%.0f", WCD)
			end
		end
		if champion:CanUseSpell(_E) == NOTLEARNED then
			stateE[i] = txtNL
		elseif ECD == 0 then
			stateE[i] = 0
		else
			if IsKeyDown(UIHK) then
				stateE[i] = TimerText(ECD+GetInGameTimer())
			else
				stateE[i] = string.format("%.0f", ECD)
			end
		end
		if champion:CanUseSpell(_R) == NOTLEARNED then
			stateR[i] = txtNL
		elseif RCD == 0 then
			stateR[i] = 0
		else
			if IsKeyDown(UIHK) then
				stateR[i] = TimerText(RCD+GetInGameTimer())
			else
				stateR[i] = string.format("%.0f", RCD)
			end
		end
		if S1CD == 0 then
			stateS1[i] = 0
		else
			if IsKeyDown(UIHK) then
				stateS1[i] = TimerText(S1CD+GetInGameTimer())
			else
				stateS1[i] = string.format("%.0f", S1CD)
			end
		end
		if S2CD == 0 then
			stateS2[i] = 0
		else
			if IsKeyDown(UIHK) then
				stateS2[i] = TimerText(S2CD+GetInGameTimer())
			else
				stateS2[i] = string.format("%.0f", S2CD)
			end
		end
	end
end
function OnDraw()
	if moveui.me then textPos.me = {x = GetCursorPos().x-60, y = GetCursorPos().y-3} end
	for i=1,#moveui.allies do
		if moveui.allies[i] then textPos.allies[i] = {x = GetCursorPos().x-60, y = GetCursorPos().y-3} end
	end
	for i=1,#moveui.enemies do
		if moveui.enemies[i] then textPos.enemies[i] = {x = GetCursorPos().x-60, y = GetCursorPos().y-3} end
	end
	local shownocd = not CDCConfig.ShowOnCD or IsKeyDown(UIHK)
	for i=1, heroManager.iCount do
		local champion = heroManager:GetHero(i)
		local colorQ = stateQ[i] == 0 and colorGreen or colorRed
		local colorW = stateW[i] == 0 and colorGreen or colorRed
		local colorE = stateE[i] == 0 and colorGreen or colorRed
		local colorR = stateR[i] == 0 and colorGreen or colorRed
		local colorS1 = stateS1[i] == 0 and colorGreen or colorRed
		local colorS2 = stateS2[i] == 0 and colorGreen or colorRed
		local nameS1 = champion:GetSpellData(SUMMONER_1).name == "teleportcancel" and "SummonerTeleport" or champion:GetSpellData(SUMMONER_1).name
		local nameS2 = champion:GetSpellData(SUMMONER_2).name == "teleportcancel" and "SummonerTeleport" or champion:GetSpellData(SUMMONER_2).name
		if (champion.isMe and CDCConfig.ShowMyCD) or (not champion.isMe and referenceallyi[i] and CDCConfig.Allies["ShowCD"..referencei[i]]) or (not referenceallyi[i] and CDCConfig.Enemies["ShowCD"..referencei[i]]) then
			local textPosx, textPosy = 0, 0
			if champion.isMe then textPosx, textPosy = textPos.me.x, textPos.me.y
			elseif referenceallyi[i] then textPosx, textPosy = textPos.allies[referencei[i]].x, textPos.allies[referencei[i]].y
			else textPosx, textPosy = textPos.enemies[referencei[i]].x, textPos.enemies[referencei[i]].y end
			if not CDCConfig.ShowRS1S2d then
				if shownocd or (stateQ[i] ~= 0 and stateQ[i] ~= txtNL) or (stateW[i] ~= 0 and stateW[i] ~= txtNL) or (stateE[i] ~= 0 and stateE[i] ~= txtNL) or (stateR[i] ~= 0 and stateR[i] ~= txtNL) or (stateS1[i] ~= 0 and stateS1[i] ~= txtNL) or (stateS2[i] ~= 0 and stateS2[i] ~= txtNL) then
					spritesTable[i]["Champion"]:Draw(textPosx,textPosy,CDCConfig.sAlpha)
					DrawText(champion.charName.." ("..champion.level..")", stext, textPosx+20, textPosy, colorGreen)
				end
				if shownocd or (stateQ[i] ~= 0 and stateQ[i] ~= txtNL) then
					spritesTable[i]["Q"]:Draw(textPosx,textPosy+stext,CDCConfig.sAlpha)
					DrawText("      "..champion:GetSpellData(_Q).level..":", stext, textPosx, textPosy+stext, colorGreen)
					DrawText(""..stateQ[i], stext, textPosx+30, textPosy+stext, colorQ)
				end
				if shownocd or (stateW[i] ~= 0 and stateW[i] ~= txtNL) then
					spritesTable[i]["W"]:Draw(textPosx+60,textPosy+stext,CDCConfig.sAlpha)
					DrawText(""..champion:GetSpellData(_W).level..":", stext, textPosx+60+18, textPosy+stext, colorGreen)
					DrawText(""..stateW[i], stext, textPosx+60+35, textPosy+stext, colorW)
				end
				if shownocd or (stateE[i] ~= 0 and stateE[i] ~= txtNL) then
					spritesTable[i]["E"]:Draw(textPosx,textPosy+stext*2,CDCConfig.sAlpha)
					DrawText(""..champion:GetSpellData(_E).level..":", stext, textPosx+18, textPosy+stext*2, colorGreen)
					DrawText(""..stateE[i], stext, textPosx+30, textPosy+stext*2, colorE)
				end
				if shownocd or (stateR[i] ~= 0 and stateR[i] ~= txtNL) then
					spritesTable[i]["R"]:Draw(textPosx+60,textPosy+stext*2,CDCConfig.sAlpha)
					DrawText(""..champion:GetSpellData(_R).level..":", stext, textPosx+60+18, textPosy+stext*2, colorGreen)
					DrawText(""..stateR[i], stext, textPosx+60+35, textPosy+stext*2, colorR)
				end
				if shownocd or (stateS1[i] ~= 0 and stateS1[i] ~= txtNL) then
					spritesTable[nameS1]:Draw(textPosx,textPosy+stext*3,CDCConfig.sAlpha)
					DrawText(""..":", stext, textPosx+25, textPosy+stext*3, colorGreen)
					DrawText(""..stateS1[i], stext, textPosx+30, textPosy+stext*3, colorS1)
				end
				if shownocd or (stateS2[i] ~= 0 and stateS2[i] ~= txtNL) then
					spritesTable[nameS2]:Draw(textPosx+60,textPosy+stext*3,CDCConfig.sAlpha)
					DrawText(""..":", stext, textPosx+60+25, textPosy+stext*3, colorGreen)
					DrawText(""..stateS2[i], stext, textPosx+60+35, textPosy+stext*3, colorS2)
				end
			else
				if shownocd or (stateR[i] ~= 0 and stateR[i] ~= txtNL) or (stateS1[i] ~= 0 and stateS1[i] ~= txtNL) or (stateS2[i] ~= 0 and stateS2[i] ~= txtNL) then
					spritesTable[i]["Champion"]:Draw(textPosx,textPosy,CDCConfig.sAlpha)
					DrawText(champion.charName.." ("..champion.level..")", stext, textPosx+20, textPosy, colorGreen)
				end
				if shownocd or (stateR[i] ~= 0 and stateR[i] ~= txtNL) then
					spritesTable[i]["R"]:Draw(textPosx,textPosy+stext,CDCConfig.sAlpha)
					DrawText(""..champion:GetSpellData(_R).level..":", stext, textPosx+18, textPosy+stext, colorGreen)
					DrawText(""..stateR[i], stext, textPosx+35, textPosy+stext, colorR)
				end
				if shownocd or (stateS1[i] ~= 0 and stateS1[i] ~= txtNL) then
					spritesTable[nameS1]:Draw(textPosx,textPosy+stext*2,CDCConfig.sAlpha)
					DrawText(""..":", stext, textPosx+25, textPosy+stext*2, colorGreen)
					DrawText(""..stateS1[i], stext, textPosx+35, textPosy+stext*2, colorS1)
				end
				if shownocd or (stateS2[i] ~= 0 and stateS2[i] ~= txtNL) then
					spritesTable[nameS2]:Draw(textPosx,textPosy+stext*3,CDCConfig.sAlpha)
					DrawText(""..":", stext, textPosx+25, textPosy+stext*3, colorGreen)
					DrawText(""..stateS2[i], stext, textPosx+35, textPosy+stext*3, colorS2)
				end
			end
		end
		if (champion.isMe and CDCConfig.ShowMyCD2) or (not champion.isMe and referenceallyi[i] and CDCConfig.Allies["ShowCD2"..referencei[i]]) or (not referenceallyi[i] and CDCConfig.Enemies["ShowCD2"..referencei[i]]) then	
			if not champion.dead and champion.visible then
				local hpBarPos = GetHPBarPos(champion)
				local Height1 = 32
				if OnScreen(hpBarPos.x, hpBarPos.y) or OnScreen(hpBarPos.x + 115, hpBarPos.y) then
					if not CDCConfig.ShowRS1S2c then
						if shownocd or (stateQ[i] ~= 0 and stateQ[i] ~= txtNL) then
							DrawRectangle(hpBarPos.x - 1, hpBarPos.y - Height1 - 1, 45, stext + 2, ARGB(CDCConfig.bAlpha,0,0,0))
							spritesTable[i]["Q"]:Draw(hpBarPos.x,hpBarPos.y - Height1,CDCConfig.sAlpha)
							DrawText(" "..stateQ[i], stext, hpBarPos.x+15, hpBarPos.y - Height1, colorQ)
						end
						if shownocd or (stateW[i] ~= 0 and stateW[i] ~= txtNL) then
							DrawRectangle(hpBarPos.x + 50 - 1, hpBarPos.y - Height1 - 1, 45, stext + 2, ARGB(CDCConfig.bAlpha,0,0,0))
							spritesTable[i]["W"]:Draw(hpBarPos.x+50,hpBarPos.y - Height1,CDCConfig.sAlpha)
							DrawText(" "..stateW[i], stext, hpBarPos.x+65, hpBarPos.y - Height1, colorW)
						end
						if shownocd or (stateE[i] ~= 0 and stateE[i] ~= txtNL) then
							DrawRectangle(hpBarPos.x + 100 - 1, hpBarPos.y - Height1 - 1, 45, stext + 2, ARGB(CDCConfig.bAlpha,0,0,0))
							spritesTable[i]["E"]:Draw(hpBarPos.x+100,hpBarPos.y - Height1,CDCConfig.sAlpha)
							DrawText(" "..stateE[i], stext, hpBarPos.x+115, hpBarPos.y - Height1, colorE)
						end
					end	
					if shownocd or (stateR[i] ~= 0 and stateR[i] ~= txtNL) then
						DrawRectangle(hpBarPos.x - 1, hpBarPos.y - Height1 + stext - 1, 45, stext + 2, ARGB(CDCConfig.bAlpha,0,0,0))
						spritesTable[i]["R"]:Draw(hpBarPos.x,hpBarPos.y - Height1 + stext,CDCConfig.sAlpha)
						DrawText(" "..stateR[i], stext, hpBarPos.x+15, hpBarPos.y - Height1 + stext, colorR)
					end
					if shownocd or (stateS1[i] ~= 0 and stateS1[i] ~= txtNL) then
						DrawRectangle(hpBarPos.x + 50 - 1, hpBarPos.y - Height1 + stext - 1, 45, stext + 2, ARGB(CDCConfig.bAlpha,0,0,0))
						spritesTable[nameS1]:Draw(hpBarPos.x+50,hpBarPos.y - Height1 + stext,CDCConfig.sAlpha)
						DrawText(" "..stateS1[i], stext, hpBarPos.x+65, hpBarPos.y - Height1 + stext, colorS1)
					end
					if shownocd or (stateS2[i] ~= 0 and stateS2[i] ~= txtNL) then
						DrawRectangle(hpBarPos.x + 100 - 1, hpBarPos.y - Height1 + stext - 1, 45, stext + 2, ARGB(CDCConfig.bAlpha,0,0,0))
						spritesTable[nameS2]:Draw(hpBarPos.x+100,hpBarPos.y - Height1 + stext,CDCConfig.sAlpha)
						DrawText(" "..stateS2[i], stext, hpBarPos.x+115, hpBarPos.y - Height1 + stext, colorS2)
					end
				end
			end
		end
	end
end
function OnWndMsg(msg,key)
	if msg == WM_LBUTTONUP or not IsKeyDown(UIHK) then
		for i=1,#moveui.allies do
			if moveui.allies[i] then writeConfigstextPos() end
			moveui.allies[i] = false
		end
		for i=1,#moveui.enemies do
			if moveui.enemies[i] then writeConfigstextPos() end
			moveui.enemies[i] = false
		end
		if moveui.me then writeConfigstextPos() end
		moveui.me = false
	end
    if msg == WM_LBUTTONDOWN and IsKeyDown(UIHK) then
		for i=1, heroManager.iCount do
			local champion = heroManager:GetHero(i)
			if (champion.isMe and CDCConfig.ShowMyCD) or (referenceallyi[i] and CDCConfig.Allies["ShowCD"..referencei[i]]) or (not referenceallyi[i] and CDCConfig.Enemies["ShowCD"..referencei[i]]) then
				local textPosx, textPosy = 0, 0
				if champion.isMe then textPosx, textPosy = textPos.me.x, textPos.me.y
				elseif referenceallyi[i] then textPosx, textPosy = textPos.allies[referencei[i]].x, textPos.allies[referencei[i]].y
				else textPosx, textPosy = textPos.enemies[referencei[i]].x, textPos.enemies[referencei[i]].y end
				if CursorIsUnder(textPosx, textPosy, 130, stext*4) then
					if champion.isMe then moveui.me = true
					elseif referenceallyi[i] then moveui.allies[referencei[i]] = true
					else moveui.enemies[referencei[i]] = true end
					break
				end
			end
		end
	end
end
function UnLoad()
	for i=1, heroManager.iCount do
		spritesTable[i]["Champion"]:Release()
		spritesTable[i]["Q"]:Release()
		spritesTable[i]["W"]:Release()
		spritesTable[i]["E"]:Release()
		spritesTable[i]["R"]:Release()
	end
	spritesTable["SummonerBarrier"]:Release()
	spritesTable["SummonerBoost"]:Release()
	spritesTable["SummonerClairvoyance"]:Release()
	spritesTable["SummonerDot"]:Release()
	spritesTable["SummonerExhaust"]:Release()
	spritesTable["SummonerFlash"]:Release()
	spritesTable["SummonerOdinGarrison"]:Release()
	spritesTable["SummonerHaste"]:Release()
	spritesTable["SummonerHeal"]:Release()
	spritesTable["SummonerMana"]:Release()
	spritesTable["SummonerRevive"]:Release()
	spritesTable["SummonerSmite"]:Release()
	spritesTable["SummonerTeleport"]:Release()
end
function GetSpriteCC(file,SpriteType)
    if FileExist(SPRITE_PATH.."CooldownChecker\\"..file) == true then
        return GetSprite("CooldownChecker\\"..file)
    else
		if SpriteType == 1 then
			return GetSprite("CooldownChecker\\Random_0.png")
		else
			return GetSprite("CooldownChecker\\SpellToggleIcon.png")
		end
    end
end