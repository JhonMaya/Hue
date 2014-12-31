<?php exit() ?>--by Che 91.8.167.39


1
2
3
4
5
6
7
8
9
10
11
12
13
14
15
16
17
18
19
20
21
22
23
24
25
26
27
28
29
30
31
32
33
34
35
36
37
38
39
40
41
42
43
44
45
46
47
48
49
50
51
52
53
54
55
56
57
58
59
60
61
62
63
64
65
66
67
68
69
70
71
72
73
74
75
76
77
78
79
80
81
82
83
84
85
86
87
88
89
90
91
92
93
94
95
96
97
98
99
100
101
102
103
104
105
106
107
108
109
110
111
112
113
114
115
116
117
118
119
120
121
122
123
124
125
126
127
128
129
130
131
132
133
134
135
136
137
138
139
140
141
142
143
144
145
146
147
148
149
150
151
152
153
154
155
156
157
158
159
160
161
162
163
164
165
166
167
168
169
170
171
172
173
174
175
176
177
178
179
180
181
182
183
184
185
186
187
188
189
190
191
192
193
194
195
196
197
198
199
200
201
202
203
204
205
206
207
208
209
210
211
212
213
214
215
216
217
218
219
220
221
222
223
224
225
226
227
228
229
230
231
232
233
234
235
236
237
238
239
240
241
242
243
244
245
246
247
248
249
250
251
252
253
254
255
256
257
258
259
260
261
262
263
264
265
266
267
268
269
270
271
272
273
274
275
276
277
278
279
280
281
282
283
284
285
286
287
288
289
290
291
292
293
294
295
296
297
298
299
300
301
302
303
304
305
306
307
308
309
310
311
312
313
314
315
316
317
318
319
320
321
322
323
324
325
326
327
328
329
330
331
332
333
334
335
336
337
338
339
340
341
342
343
344
345
346
347
348
349
350
351
352
353
354
355
356
357
358
359
360
361
362
363
364
365
366
367
368
369
370
371
372
373
374
375
376
377
378
379
380
381
382
383
384
385
386
387
388
389
390
391
392
393
394
395
396
397
398
399
400
401
402
403
404
405
406
407
408
409
410
411
412
413
414
415
416
417
418
419
420
421
422
423
424
425
426
427
428
429
430
431
432
433
434
435
436
437
438
439
440
441
442
443
444
445
446
447
448
449
450
451
452
453
454
455
456
457
458
459
460
461
462
463
464
465
466
467
468
469
470
471
472
473
474
475
476
477
478
479
480
481
482
483
484
485
486
487
488
489
490
491
492
493
494
495
496
497
498
499
500
501
502
503
504
505

-- // Auto Update // --
local version = "2.10"

if myHero.charName ~= "Ahri" or not VIP_USER then return end

_G.UseUpdater = true

local REQUIRED_LIBS = {
	["SOW"] = "https://raw.githubusercontent.com/Hellsing/BoL/master/common/SOW.lua",
	["VPrediction"] = "https://raw.githubusercontent.com/Hellsing/BoL/master/common/VPrediction.lua"
}

local DOWNLOADING_LIBS, DOWNLOAD_COUNT = false, 0

function AfterDownload()
	DOWNLOAD_COUNT = DOWNLOAD_COUNT - 1
	if DOWNLOAD_COUNT == 0 then
		DOWNLOADING_LIBS = false
		print("<b><font color=\"Required libraries downloaded successfully, please reload (Double F9)")
	end
end

for DOWNLOAD_LIB_NAME, DOWNLOAD_LIB_URL in pairs(REQUIRED_LIBS) do
	if FileExist(LIB_PATH .. DOWNLOAD_LIB_NAME .. ".lua") then
		require(DOWNLOAD_LIB_NAME)
	else
		DOWNLOADING_LIBS = true
		DOWNLOAD_COUNT = DOWNLOAD_COUNT + 1
		DownloadFile(DOWNLOAD_LIB_URL, LIB_PATH .. DOWNLOAD_LIB_NAME..".lua", AfterDownload)
	end
end

if DOWNLOADING_LIBS then return end

local Autoupdate = true
local UPDATE_SCRIPT_NAME = "JustAhri"
local UPDATE_HOST = "raw.github.com"
local UPDATE_PATH = "/Galaxix/BoLScripts/master/JustAhri.lua".."?rand="..math.random(1,10000)
local UPDATE_FILE_PATH = SCRIPT_PATH..GetCurrentEnv().FILE_NAME
local UPDATE_URL = "https://"..UPDATE_HOST..UPDATE_PATH

function AutoupdaterMsg(msg) print("<font color=\"#FF0000\">"..UPDATE_SCRIPT_NAME..":</font> <font color=\"#FFFFFF\">"..msg..".</font>") end
if Autoupdate then
        local ServerData = GetWebResult(UPDATE_HOST, UPDATE_PATH)
        if ServerData then
                local ServerVersion = string.match(ServerData, "local version = \"%d+.%d+\"")
                ServerVersion = string.match(ServerVersion and ServerVersion or "", "%d+.%d+")
                if ServerVersion then
                        ServerVersion = tonumber(ServerVersion)
                        if tonumber(version) < ServerVersion then
                                AutoupdaterMsg("New version available"..ServerVersion)
                                AutoupdaterMsg("Updating, please don't press F9")
                                DownloadFile(UPDATE_URL, UPDATE_FILE_PATH, function () AutoupdaterMsg("Successfully updated. ("..version.." => "..ServerVersion.."), press F9 twice to load the updated version.") end)  
                        else
                                AutoupdaterMsg("You have got the latest version ("..ServerVersion..")")
                        end
                end
        else
                AutoupdaterMsg("Error downloading version info, please manually update it.")
        end
end
-- // End of Auto Update // --

local Menu = nil  

function Data()
	Recalling = false
	Spell = {
		Q = {range = 880, delay = 0.25, speed = 1100, width = 80},
		W = {range = 800, delay = nil, speed = math.huge, width = nil},
		E = {range = 975, delay = 0.25, speed = 1200,  width = 100},
		R = {range = 550, delay = nil, speed = math.huge, width = 190}
	}
	IgniteSlot = ((myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") and SUMMONER_1) or (myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") and SUMMONER_2) or nil)
end

function OnLoad()
	--{ Variables
	Data()
	myHero = GetMyHero()
	
	-- Orbwalk & VPrediction
	VP = VPrediction()
	OW = SOW(VP)
	
	--Target
	Col = Collision(Spell.E.range,Spell.E.speed,Spell.E.delay,Spell.E.width)
	ts = TargetSelector(TARGET_LESS_CAST,975,DAMAGE_MAGIC,false)
	ts.name = "AllClass TS"
	
	-- Minion & Jungle Mob
	EnemyMinion = minionManager(MINION_ENEMY,Spell.Q.range,myHero,MINION_SORT_HEALTH_ASC)
	JungMinion = minionManager(MINION_JUNGLE, Spell.Q.range, myHero, MINION_SORT_MAXHEALTH_DEC)
	
	--{ Create Menu
	Menu = scriptConfig("JustAhri","JustAhri")
		--{ Script Information
		Menu:addSubMenu("[ JustAhri : Script Information]","Script")
		Menu.Script:addParam("Author","         Author: Galaxix",SCRIPT_PARAM_INFO,"")
		Menu.Script:addParam("Credits","        Credits: Lazer, Honda, AWA[ BEST ]",SCRIPT_PARAM_INFO,"")
		Menu.Script:addParam("Version","         Version: 2.10 ",SCRIPT_PARAM_INFO,"")
		--}
		
		--{ General/Key Bindings
		Menu:addSubMenu("[ JustAhri : General ]","General")
		Menu.General:addParam("Combo","Combo",SCRIPT_PARAM_ONKEYDOWN,false,32)
		Menu.General:addParam("KillSteal","Smart KillSteal",SCRIPT_PARAM_ONOFF,true)
		Menu.General:addParam("Harass","Harass",SCRIPT_PARAM_ONKEYDOWN,false,string.byte("G"))
		--}
		
		--{ Target Selector
		Menu:addSubMenu("[ JustAhri : Target Selector ]","TS")
		Menu.TS:addParam("TS","Target Selector",SCRIPT_PARAM_LIST,1,{"AllClass","SAC: Reborn","MMA"})
		Menu.TS:addTS(ts)
		--}
		
		--{ Orbwalking
		Menu:addSubMenu("[ JustAhri : Orbwalking ]","Orbwalking")
		OW:LoadToMenu(Menu.Orbwalking)
		--}
		
		--{ Combo Settings
		Menu:addSubMenu("[ JustAhri : Combo ]","Combo")
		Menu.Combo:addParam("Q","Use Q in combo",SCRIPT_PARAM_ONOFF,true)
		Menu.Combo:addParam("E","Use E in combo",SCRIPT_PARAM_ONOFF,true)
		Menu.Combo:addParam("W","Use W in combo",SCRIPT_PARAM_ONOFF,true)
		Menu.Combo:addParam("I","Use Items in combo",SCRIPT_PARAM_ONOFF,true)
		Menu.Combo:addParam("R", "Use R in Combo", SCRIPT_PARAM_LIST, 2, { "To Mouse", "To Enemy", "Don't Use It !"})
		Menu.Combo:addParam("RequireCharm","Require Charm (J)", SCRIPT_PARAM_ONKEYTOGGLE, true, string.byte("J"))
		--}
		
		--{ Harass Settings
		Menu:addSubMenu("[ JustAhri : Harass ]","Harass")
		Menu.Harass:addParam("Q","Use Q in 'Harass'",SCRIPT_PARAM_ONOFF,true)
		Menu.Harass:addParam("E","Use E in 'Harass'",SCRIPT_PARAM_ONOFF,false)
		Menu.Harass:addParam("W","Use W in 'Harass'",SCRIPT_PARAM_ONOFF,false)
		Menu.Harass:addParam("HMana","Don't harass if mana < %",SCRIPT_PARAM_SLICE,20,0,100)
		--}
		
		--{ Draw Settings
		Menu:addSubMenu("[ JustAhri : Draw ]","Draw")
		Menu.Draw:addSubMenu("Skill Info","Skill")
		
		Menu.Draw.Skill:addParam("QRange","Draw Q Range",SCRIPT_PARAM_ONOFF,true)
		Menu.Draw.Skill:addParam("QColor","Set Q Color",SCRIPT_PARAM_COLOR,{255,255,0,0})
		Menu.Draw.Skill:addParam("WRange","Draw W Range",SCRIPT_PARAM_ONOFF,true)
		Menu.Draw.Skill:addParam("WColor","Set W Color",SCRIPT_PARAM_COLOR,{255,255,255,0})
		Menu.Draw.Skill:addParam("ERange","Draw E Range",SCRIPT_PARAM_ONOFF,true)
		Menu.Draw.Skill:addParam("EColor","Set E Color",SCRIPT_PARAM_COLOR,{255,0,255,0})
		Menu.Draw.Skill:addParam("RRange","Draw R Range",SCRIPT_PARAM_ONOFF,true)
		Menu.Draw.Skill:addParam("RColor","Set R Color",SCRIPT_PARAM_COLOR,{255,0,255,255})
		Menu.Draw:addParam("LFC","Use Lag free circle",SCRIPT_PARAM_ONOFF,true)
		--}
		
		--{ Extra Settings
		Menu:addSubMenu("[ JustAhri : Extra Settings ]","Extra")
		Menu.Extra:addParam("AutoI","Auto Ignite on killable enemy",SCRIPT_PARAM_ONOFF,true)
		Menu.Extra:addParam("AutoE","Auto E GapClosers",SCRIPT_PARAM_ONOFF,false)
		Menu.Extra:addParam("Packet","Use Packets",SCRIPT_PARAM_ONOFF,false)
		
		--}
		
		--{ Prediction Mode
		Menu:addSubMenu("[ JustAhri : Prediction Setting ]","Predict")
			Menu.Predict:addParam("G","[General Prediction Settings]",SCRIPT_PARAM_INFO,"")
			Menu.Predict:addParam("Mode","Prediction Mode",SCRIPT_PARAM_LIST,1,{"VPrediction","Prodiction"})
			Menu.Predict:addParam("D","[Detail Prediction Settings]",SCRIPT_PARAM_INFO,"")
			Menu.Predict:addParam("VPHitChance","VPrediction HitChance",SCRIPT_PARAM_LIST,3,{"[0]Target Position","[1]Low Hitchance","[2]High Hitchance","[3]Target slowed/close","[4]Target immobile","[5]Target Dashing"})
		--}
		
		--{ Perma Show
		Menu.Script:permaShow("Author")
		Menu.Script:permaShow("Version")
		Menu.General:permaShow("Combo")
		Menu.Combo:permaShow("R")
		Menu.Combo:permaShow("RequireCharm")
		Menu.General:permaShow("Harass")
		Menu.Predict:permaShow("Mode")
		--}
	
	    Prodiction = ProdictManager.GetInstance()
		ProdictQ = Prodiction:AddProdictionObject(_Q,Spell.Q.range,Spell.Q.speed,Spell.Q.delay,Spell.Q.width)
		ProdictW = Prodiction:AddProdictionObject(_W,Spell.W.range,Spell.W.speed,Spell.W.delay,Spell.W.width)
		ProdictE = Prodiction:AddProdictionObject(_E,Spell.E.range,Spell.E.speed,Spell.E.delay,Spell.E.width)
		ProdictR = Prodiction:AddProdictionObject(_R,Spell.R.range,Spell.R.speed,Spell.R.delay,Spell.R.width)
		
		--{ Print
		PrintChat("<font color='#FF1493'> >> JustAhri by Galaxix v2.1 BETA Loaded ! <<</font>")
		loaded = true 
		--}
		end 
		

-- OnTick Function --
   function OnTick()
	 if loaded then 
   --{ Variables
	QREADY = myHero:CanUseSpell(_Q) == READY
	WREADY = myHero:CanUseSpell(_W) == READY
	EREADY = myHero:CanUseSpell(_E) == READY  
	RREADY = myHero:CanUseSpell(_R) == READY 
	
	Target = GrabTarget()
		
	DfgSlot  = GetInventorySlotItem(3128)
	BftSlot  = GetInventorySlotItem(3188)
	
	DFGREADY = (DfgSlot ~= nil and myHero:CanUseSpell(DfgSlot) == READY)
	BFTREADY = (BftSlot ~= nil and myHero:CanUseSpell(BftSlot) == READY)
	IGNITEREADY = (IgniteSlot ~= nil and myHero:CanUseSpell(IgniteSlot) == READY)
	
	--{ Auto E when enemy hero are near
	if Menu.Extra.AutoE then
		for i = 1, heroManager.iCount do
			local hero = heroManager:GetHero(i)
			if hero.team ~= myHero.team and ValidTarget(hero,400) then
				CastE(hero)
			end
		end
	end
	
	--}

    if Menu.General.Combo then
      Combo(Target)
    end
    
     if Menu.General.Harass then
      Harass(Target)
    end
    
    if Menu.General.KillSteal then
      KillSteal()
    end
  
  if Menu.Extra.AutoI then
		for i = 1, heroManager.iCount do
			local hero = heroManager:GetHero(i)
			if hero.team ~= myHero.team and ValidTarget(hero,650) and getDmg("IGNITE",hero,myHero) > hero.health then
				CastSpell(IgniteSlot,hero)
			end
		end
	end
	end 
	end 
	
-- OnDraw --
function OnDraw()
  if loaded then 
	if QREADY and Menu.Draw.Skill.QRange then
		DrawCircle2(myHero.x,myHero.y,myHero.z,Spell.Q.range,ARGB(Menu.Draw.Skill.QColor[1],Menu.Draw.Skill.QColor[2],Menu.Draw.Skill.QColor[3],Menu.Draw.Skill.QColor[4]))
	end
	if WREADY and Menu.Draw.Skill.WRange then
		DrawCircle2(myHero.x,myHero.y,myHero.z,Spell.W.range,ARGB(Menu.Draw.Skill.WColor[1],Menu.Draw.Skill.WColor[2],Menu.Draw.Skill.WColor[3],Menu.Draw.Skill.WColor[4]))
	end
	if EREADY and Menu.Draw.Skill.ERange then
		DrawCircle2(myHero.x,myHero.y,myHero.z,Spell.E.range,ARGB(Menu.Draw.Skill.EColor[1],Menu.Draw.Skill.EColor[2],Menu.Draw.Skill.EColor[3],Menu.Draw.Skill.EColor[4]))
	end
	if RREADY and Menu.Draw.Skill.RRange then
		DrawCircle2(myHero.x,myHero.y,myHero.z,Spell.R.range,ARGB(Menu.Draw.Skill.RColor[1],Menu.Draw.Skill.RColor[2],Menu.Draw.Skill.RColor[3],Menu.Draw.Skill.RColor[4]))
	end
	    end
			end 
	
--{ Target Selector
function GrabTarget()
	if _G.MMA_Loaded and Menu.TS.TS == 3 then
		return _G.MMA_ConsideredTarget(MaxRange())		
	elseif _G.AutoCarry and Menu.TS.TS == 2 then
		return _G.AutoCarry.Crosshair:GetTarget()
	else
		ts.range = MaxRange()
		ts:update()
		return ts.target
	end
end

--{ Prediction Cast
function SpellCast(spellSlot,castPosition)
	if Menu.Extra.Packet then
		Packet("S_CAST", {spellId = spellSlot, fromX = castPosition.x, fromY = castPosition.z, toX = castPosition.x, toY = castPosition.z}):send()
	end
end
--}

--{ Enemy in range of myHero
function CountEnemyInRange(target,range)
	local count = 0
	for i = 1, heroManager.iCount do
		local hero = heroManager:GetHero(i)
		if hero.team ~= myHero.team and hero.visible and not hero.dead and GetDistanceSqr(target,hero) <= range*range then
			count = count + 1
		end
	end
	return count
end
--}

-- Combo Function --
function Combo(unit)
 if ValidTarget(unit) and unit ~= nil and unit.type == myHero.type then
	
	    if Menu.Combo.R and RREADY and GetDistance(unit) <= Spell.R.range then CastR(unit) end
		if Menu.Combo.E and EREADY and GetDistance(unit) <= Spell.E.range then CastE(unit) end
		if charmCheck() then return end
		if Menu.Combo.I then UseItems(unit) end
		if Menu.Combo.Q and QREADY and GetDistance(unit) <= Spell.Q.range then CastQ(unit) end
		if Menu.Combo.W and WREADY and GetDistance(unit) <= Spell.W.range then CastW(unit) end
	end
 end
 
 -- Harass function --
function Harass(unit)
        if ValidTarget(unit) and unit ~= nil and unit.type == myHero.type and not IsMyManaLow() then
                if Menu.Harass.E and EREADY then CastE(unit) end
                if charmCheck() then return end
                if Menu.Harass.Q and QREADY then CastQ(unit) end
                if Menu.Harass.W and WREADY then CastW(unit) end
        end
end

-- Harass Mana Function by Kain--
function IsMyManaLow()
	if myHero.mana < (myHero.maxMana * (Menu.Harass.HMana / 100)) then
		return true
	else
		return false
	end
end

-- Use Items
function UseItems(unit)
if Menu.Combo.I and GetDistanceSqr(myHero,Target) <= 750 * 750 then
				if DFGREADY then
					CastSpell(DfgSlot,Target)
				end

				if BFTREADY then
					CastSpell(BftSlot,Target)
				end
			end
end

function CastQ(unit)
        if unit ~= nil and GetDistance(unit) <= Spell.Q.range and QREADY then
        	--Vprediction
        	if Menu.Predict.Mode == 1 then
                local CastPosition,  HitChance,  Position = VP:GetLineCastPosition(unit, Spell.Q.delay, Spell.Q.width, Spell.Q.range, Spell.Q.speed, myHero)
				if CastPosition ~= nil and HitChance >= (Menu.Predict.VPHitChance - 1) then
				SpellCast(_Q,CastPosition)
				end
			-- Prodiction
			elseif Menu.Predict.Mode == 2 then
				local CastPosition = ProdictQ:GetPrediction(unit)
				if CastPosition ~= nil then
					SpellCast(_Q,CastPosition)
				end
           end
     end
end

function CastE(unit)
        if unit ~= nil and GetDistance(unit) <= Spell.E.range and EREADY then
            -- VPrediction
			if Menu.Predict.Mode == 1 then
				local CastPosition,  HitChance,  Position = VP:GetLineCastPosition(unit, Spell.E.delay, Spell.E.width, Spell.E.range, Spell.E.speed, myHero, true)
				if CastPosition ~= nil and HitChance >= (Menu.Predict.VPHitChance -1) then
					SpellCast(_E,CastPosition)
				end
			-- Prodiction
			elseif Menu.Predict.Mode == 2 then
			local isCol,ColTable = Col:GetCollision(myHero,unit)
		    if #ColTable <= 1 then	
			local CastPosition = ProdictE:GetPrediction(unit)
				if CastPosition ~= nil then
					SpellCast(_E,CastPosition)
				end
            end
         end
     end
end

function CastW(unit)
        if unit ~= nil and myHero:CanUseSpell(_W) == READY and GetDistance(unit) <= Spell.W.range then
                Packet("S_CAST", {spellId = _W}):send()
      end
end

function CastR(unit)
        if RREADY and GetDistance(unit) <= Spell.R.range and Menu.Combo.R == 1 then
                local Mouse = Vector(myHero) + 400 * (Vector(mousePos) - Vector(myHero)):normalized()
                CastSpell(_R, Mouse.x, Mouse.z) 
        elseif RREADY and GetDistance(unit) <= Spell.R.range and Menu.Combo.R == 2 then
                CastSpell(_R, unit.x, unit.z)
        elseif Menu.Combo.R == 3 then
                return
       end
end

--{ Lag Free circle credits: vadash,ViceVersa,barasia283
function DrawCircleNextLvl(x, y, z, radius, width, color, chordlength)
	radius = radius or 300
	quality = math.max(8,math.floor(180/math.deg((math.asin((chordlength/(2*radius)))))))
	quality = 2 * math.pi / quality
	radius = radius*.92
	local points = {}
	for theta = 0, 2 * math.pi + quality, quality do
		local c = WorldToScreen(D3DXVECTOR3(x + radius * math.cos(theta), y, z - radius * math.sin(theta)))
		points[#points + 1] = D3DXVECTOR2(c.x, c.y)
	end
	DrawLines2(points, width or 1, color or 4294967295)
end


function DrawCircle2(x, y, z, radius, color)
	local vPos1 = Vector(x, y, z)
	local vPos2 = Vector(cameraPos.x, cameraPos.y, cameraPos.z)
	local tPos = vPos1 - (vPos1 - vPos2):normalized() * radius
	local sPos = WorldToScreen(D3DXVECTOR3(tPos.x, tPos.y, tPos.z))
	if Menu.Draw.LFC and OnScreen({ x = sPos.x, y = sPos.y }, { x = sPos.x, y = sPos.y })  then
		DrawCircleNextLvl(x, y, z, radius, 1, color, 75)
	else
		DrawCircle(x,y,z,radius,color)
	end
end
--}

--KillSteal
function KillSteal()
        for _, enemy in ipairs(GetEnemyHeroes()) do
                qDmg = getDmg("Q", enemy, myHero)
                eDmg = getDmg("E", enemy, myHero)
                wDmg = getDmg("W", enemy, myHero)
                
                if ValidTarget(enemy) and enemy.visible then
                        if enemy.health <= qDmg then
                                CastQ(enemy)
                        elseif enemy.health <= qDmg + eDmg then
                                CastE(enemy)
                                CastQ(enemy)
                        elseif enemy.health <= eDmg then
                                CastE(enemy)
                        elseif enemy.health <= wDmg then
                                CastW(enemy)
                        elseif enemy.health <= eDmg + qDmg + wDmg then
                                CastE(enemy)
                                CastQ(enemy)
                                CastW(enemy)
                        elseif enemy.health <= qDmg + wDmg then
                                CastQ(enemy)
                                CastW(enemy)
                        end

         end
     end
end
--}

function OnCreateObj(obj)
	if obj.name:find("TeleportHome") then
		Recalling = true
	end
end

function OnDeleteObj(obj)
	if obj.name:find("TeleportHome") or (Recalling == nil and obj.name == Recalling.name) then
		Recalling = false
	end
end

function OnGainBuff(unit, buff)
	if unit.isMe then
		if buff.name == "AhriTumble" then
			AhriTumbleActive = true
		end
	end
end

function OnLoseBuff(unit, buff)
	if unit.isMe then
		if buff.name == "AhriTumble" then
			AhriTumbleActive = false
		end
	end
end

function charmCheck()
	if EREADY and Menu.Combo.RequireCharm then 
		return true
	else
		return false
	end
end

function MaxRange()
	if QREADY then
		return Spell.Q.range
	elseif EREADY then
		return Spell.E.range
	else
		return myHero.range + 50
	end
end
--}

